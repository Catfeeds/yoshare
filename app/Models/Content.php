<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JPush\Client as JPush;
use Request;
use Response;

class Content extends Model
{
    use SoftDeletes;

    const TYPE_SMALL = 1;
    const TYPE_LARGE = 2;
    const TYPE_MULTI = 3;

    const STATE_DELETED = 0;
    const STATE_NORMAL = 1;
    const STATE_CANCELED = 2;
    const STATE_PUBLISHED = 9;

    const STATES = [
        0 => '已删除',
        1 => '未发布',
        2 => '已撤回',
        9 => '已发布',
    ];

    const TAG_TOP = '置顶';

    const LINK_TYPE_NONE = 0;
    const LINK_TYPE_WEB = 1;

    protected $fillable = [
        'site_id',
        'category_id',
        'type',
        'title',
        'subtitle',
        'keywords',
        'slug',
        'image_url',
        'video_url',
        'audio_url',
        'live_url',
        'video_duration',
        'author',
        'tags',
        'source',
        'link_type',
        'link',
        'summary',
        'content',
        'memo',
        'comments',
        'favorites',
        'clicks',
        'views',
        'state',
        'sort',
        'sync_id',
        'member_id',
        'user_id',
        'published_at',
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $dates = ['published_at', 'delete_at'];

    public function setPublishedAtAttribute($date)
    {
        if (empty($date)) {
            $this->attributes['published_at'] = null;
        } else {
            $this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        }
    }

    public function stateName()
    {
        return array_key_exists($this->state, static::STATES) ? static::STATES[$this->state] : '';
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function previous()
    {
        return Content::where('site_id', $this->site_id)
            ->where('category_id', $this->category_id)
            ->where('state', $this->state)
            ->where('sort', '<', $this->sort)
            ->orderBy('sort', 'desc')
            ->first();
    }

    public function next()
    {
        return Content::where('site_id', $this->site_id)
            ->where('category_id', $this->category_id)
            ->where('state', $this->state)
            ->where('sort', '>', $this->sort)
            ->orderBy('sort')
            ->first();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'refer');
    }

    public function scopeOwns($query)
    {
        $query->where('site_id', Auth::user()->site_id);
    }

    /**
     * 条件过滤
     *
     * @param $query
     * @param $filters
     */
    public function scopeFilter($query, $filters)
    {
        $query->where(function ($query) use ($filters) {
            empty($filters['id']) ?: $query->where('id', $filters['id']);
            empty($filters['title']) ?: $query->where('title', 'like', '%' . $filters['title'] . '%');
            empty($filters['username']) ?: $query->where('username', $filters['username']);
            empty($filters['start_date']) ?: $query->where('published_at', '>=', $filters['start_date']);
            empty($filters['end_date']) ?: $query->where('published_at', '<=', $filters['end_date']);
        });
        if (isset($filters['state'])) {
            if (!empty($filters['state'])) {
                $query->where('state', $filters['state']);
            } else if ($filters['state'] === strval(static::STATE_DELETED)) {
                $query->onlyTrashed();
            }
        }
    }

    public static function getTypes()
    {
        return [
            static::TYPE_SMALL => '小图',
            static::TYPE_LARGE => '大图',
            static::TYPE_MULTI => '多图',
        ];
    }

    public static function getLinkTypes()
    {
        return [
            static::LINK_TYPE_NONE => '无',
            static::LINK_TYPE_WEB => '网址',
        ];
    }

    /**
     * 保存数据
     */
    public static function stores($module, $input)
    {
        $input['site_id'] = Auth::user()->site_id;
        $input['user_id'] = Auth::user()->id;

        $content = call_user_func([$module->model_class, 'stores'], $input);

        //保存图片集和视频集
        if (!empty($content)) {
            if (isset($input['images'])) {
                File::sync(File::TYPE_IMAGE, $content, $input['images']);

            }

            if (isset($input['audios'])) {
                File::sync(File::TYPE_AUDIO, $content, $input['audios']);
            }

            if (isset($input['videos'])) {
                File::sync(File::TYPE_VIDEO, $content, $input['videos']);
            }
        }

        return $content;
    }

    /**
     * 更新数据
     */
    public static function updates($module, $id, $input)
    {
        $content = call_user_func_array([$module->model_class, 'updates'], [$id, $input]);

        //保存图片集和视频集
        if (!empty($content)) {
            if (isset($input['images'])) {
                File::sync(File::TYPE_IMAGE, $content, $input['images']);

            }

            if (isset($input['audios'])) {
                File::sync(File::TYPE_AUDIO, $content, $input['audios']);
            }

            if (isset($input['videos'])) {
                File::sync(File::TYPE_VIDEO, $content, $input['videos']);
            }
        }

        return $content;
    }

    /**
     * 批量修改状态
     */
    public static function state($model, $input)
    {
        call_user_func([$model->model_class, 'state'], $input);
    }

    /**
     * 复制到指定栏目
     */
    public static function copy()
    {
        $category_ids = Request::get('category_ids');
        $ids = Request::get('ids');

        if (empty($category_ids)) {
            \Session::flash('flash_warning', '请选择栏目，再重试!');
            return;
        }
        if (empty($ids)) {
            \Session::flash('flash_warning', '请勾选要操作的数据!');
            return;
        }

        DB::beginTransaction();
        try {
            $contents = Content::with('items')->withTrashed()->whereIn('id', $ids)->get();
            foreach ($category_ids as $category_id) {
                foreach ($contents as $content) {
                    //复制到指定栏目
                    $original = $content->attributes;
                    $original['category_id'] = $category_id;
                    $original['comments'] = 0;
                    $original['favorites'] = 0;
                    $original['sync_id'] = $content->sync_id;
                    $original['is_top'] = 0;
                    $original['sort'] = strtotime(Carbon::now());
                    $original = static::create($original);
                    foreach ($content->items as $item) {
                        $items_is = ContentItem::find($original->id);
                        if (!$items_is) {
                            $original_item = $item->attributes;
                            $original_item['content_id'] = $original->id;
                            ContentItem::create($original_item);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        } finally {
            DB::commit();
        }

        \Session::flash('flash_success', '推荐成功!');
    }

    /**
     * 同步
     */
    public function sync()
    {
        $attributes = $this->attributes;

        DB::beginTransaction();
        try {
            if ($this->sync_id > 0) {
                //同步同级
                $contents = Content::where('sync_id', $this->sync_id)
                    ->where('id', '<>', $this->id)
                    ->get();
                foreach ($contents as $content) {
                    unset($attributes['category_id']);
                    unset($attributes['data']);
                    unset($attributes['is_top']);
                    unset($attributes['sort']);
                    unset($attributes['sync_id']);
                    $content->update($attributes);

                    //同步图集
                    $content->items()->delete();
                    foreach ($this->items as $item) {
                        $content->items()->create($item->toArray());
                    }
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        } finally {
            DB::commit();
        }
    }

    /**
     * 推送
     */
    public static function jpush($input)
    {
        $content_id = $input['content_id'];
        $ios = $input['ios'];
        $android = $input['android'];
        $tag = $input['tag'];
        $alias = $input['alias'];

        $content = Content::find($content_id);

        if (empty($content)) {
            \Session::flash('flash_warning', '无此记录');
            return redirect()->back();
        }

        $app_key = Auth::user()->site->app_key;
        $master_secret = Auth::user()->site->master_secret;
        $url = $content->link_type == Content::LINK_TYPE_WEB ? $content->link : get_url('/api/contents/share?id=' . $content_id);

        $extras = [
            'id' => $content->id,
            'title' => $content->title,
            'subtitle' => $content->subtitle,
            'image_url' => get_url($content->image_url),
            'video_url' => get_url($content->video_url),
            'images' => $content->items->where('type', ContentItem::TYPE_IMAGE)->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'url' => get_url($item->url),
                    'description' => $item->description,
                ];
            }),
            'comments' => $content->comments,
            'clicks' => $content->clicks,
            'time' => $content->published_at->toDateTimeString(),
            'url' => $url,
        ];

        //添加日志
        $data = [
            'site_id' => $content->site_id,
            'content_id' => $content->id,
            'content_type' => PushLog::TYPE_NEWS,
            'content_title' => $content->title,
            'user_id' => Auth::user()->id,
            'url' => $url,
            'state' => PushLog::STATE_SUCCESS,
        ];

        //推送
        try {
            // 初始化
            $client = new JPush($app_key, $master_secret, storage_path('') . '/logs/jpush.log');

            $pusher = $client->push();
            if ($ios > 0 && $android > 0) {
                $pusher->setPlatform('all');
            } else if ($ios > 0) {
                $pusher->setPlatform('ios');
            } else if ($android > 0) {
                $pusher->setPlatform('android');
            }
            if (!empty($tag)) {
                $pusher->addAlias($tag);
            } else if (!empty($alias)) {
                $pusher->addAlias($alias);
            } else {
                $pusher->addAllAudience();
            }
            $pusher->setNotificationAlert($content->title);
            if ($android > 0) {
                $pusher->androidNotification($content->title, [
                    'extras' => $extras,
                ]);
            }
            if ($ios > 0) {
                $pusher->iosNotification($content->title, [
                    'title' => $content->title,
                    'sound' => 'default',
                    'badge' => 1,
                    'extras' => $extras,
                ]);
            }
            $pusher->options([
                'time_to_live' => 86400,
                'apns_production' => $ios == PushLog::IOS_PUSH_PRODUCTION,
            ]);
            $result = $pusher->send();
            \Log::debug('推送结果:' . json_encode($result));

            $data['send_no'] = $result['body']['sendno'];
            $data['msg_id'] = $result['body']['msg_id'];

            return Response::json([
                'status_code' => 200,
            ]);
        } catch (Exception $e) {
            $data['state'] = PushLog::STATE_FAILURE;
            $data['err_msg'] = $e->getMessage();
            \Log::debug('推送失败:' . json_encode($e));
            return '';
        } finally {
            PushLog::create($data);
        }
    }

    /**
     * 添加/消息标记
     */
    public static function tag($id, $tag_name)
    {
        $content = Content::find($id);

        $tags = explode(',', $content->tags);
        $tags_key = array_search($tag_name, $tags);

        if ($tags_key !== false) {
            unset($tags[$tags_key]);
            $tags = implode(',', $tags);

            $content->tags = $tags;
            $content->save();
        } else {
            array_push($tags, $tag_name);
            $tags = implode(',', $tags);

            $content->tags = ltrim($tags, ',');
            $content->save();
        }
        \Session::flash('flash_success', '标记成功');

        return true;
    }

    /**
     * 置顶/取消置顶
     */
    public static function top($id)
    {
        $content = Content::find($id);
        if ($content->is_top == 1) {
            $content->is_top = 0;
            $content->save();

            \Session::flash('flash_success', '取消' . Content::TAG_TOP . '成功');
        } else {
            $content->is_top = 1;
            $content->save();

            \Session::flash('flash_success', Content::TAG_TOP . '成功');
        }

        return true;
    }

    /**
     * 增加点击数
     *
     * @param $id
     */
    public static function click($id)
    {
        //增加同步数据的点击数
        $sync_id = Content::find($id)->sync_id;
        Content::where('sync_id', $sync_id)->increment('clicks');
    }
}
