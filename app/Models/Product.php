<?php

namespace App\Models;

use Auth;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Response;

class Product extends Model
{
    const STATE_DELETED = 0;
    const STATE_NORMAL = 1;

    const TAG_SIDE = '轮播图';

    const TYPE_RECHARGE_IOS = 1;
    const TYPE_RECHARGE_ANDROID = 2;
    const TYPE_RECHARGE_WEB = 3;
    const TYPE_MEMBER_VIP = 4;
    const TYPE_SUBSCRIBE = 5;
    const TYPE_VIDEO = 6;
    const TYPE_VIDEO_PACKAGE = 7;
    const TYPE_GIFT = 8;

    const ID_APPLAUSE = 29;
    const ID_QUESTION = 33;

    const UNIT_MONTH = '月';
    const UNIT_YEAR = '年';

    protected $fillable = [
        'site_id',
        'name',
        'title',
        'type',
        'price',
        'is_member_free',
        'per',
        'unit',
        'start_time',
        'end_time',
        'image_url',
        'cover_url',
        'summary',
        'content',
        'state',
        'username',
    ];

    public function typeName()
    {
        switch ($this->type) {
            case static::TYPE_RECHARGE_IOS:
                return '苹果充值';
                break;
            case static::TYPE_RECHARGE_ANDROID:
                return '安卓充值';
                break;
            case static::TYPE_RECHARGE_WEB:
                return '网页充值';
                break;
            case static::TYPE_MEMBER_VIP:
                return 'VIP会员';
                break;
            case static::TYPE_SUBSCRIBE:
                return '订阅';
                break;
            case static::TYPE_VIDEO:
                return '视频';
                break;
            case static::TYPE_VIDEO_PACKAGE:
                return '视频包';
                break;
            case static::TYPE_GIFT:
                return '礼物';
                break;
        }
    }

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function stateName()
    {
        switch ($this->state) {
            case static::STATE_NORMAL:
                return '正常';
                break;
            case static::STATE_DELETED:
                return '已下架';
                break;
        }
    }

    public function scopeOwns($query)
    {
        $query->where('site_id', Auth::user()->site_id);
    }

    public function scopeFilter($query, $filters)
    {
        $query->where(function ($query) use ($filters) {
            !empty($filters['id']) ? $query->where('id', $filters['id']) : '';
            !empty($filters['title']) ? $query->where('title', 'like', '%' . $filters['title'] . '%') : '';
            isset($filters['state']) && $filters['state'] !== '' ? $query->where('state', $filters['state']) : '';
            !empty($filters['username']) ? $query->where('username', $filters['username']) : '';
            !empty($filters['start_date']) ? $query->where('created_at', '>=', $filters['start_date'])
                ->where('created_at', '<=', $filters['end_date']) : '';
            !empty($filters['tags']) ? $query->where('tags', 'like', '%' . $filters['tags'] . '%') : '';
        });
    }

    /**
     * 添加/取消 标记
     */
    public static function tag($id, $tag_name)
    {
        $product = Product::find($id);

        $tags = explode(',', $product->tags);
        $tags_key = array_search($tag_name, $tags);

        if ($tags_key !== false) {
            unset($tags[$tags_key]);
            $tags = implode(',', $tags);

            $product->tags = $tags;
            $product->save();

            \Session::flash('flash_success', '取消' . $tag_name . '成功');
        } else {
            array_push($tags, $tag_name);
            $tags = implode(',', $tags);

            $product->tags = ltrim($tags, ',');
            $product->save();

            \Session::flash('flash_success', '加为' . $tag_name . '成功');
        }

        return true;
    }

    /**
     * 排序
     */
    public static function sort()
    {
        $select_id = request('select_id');
        $place_id = request('place_id');
        $move_down = request('move_down');

        $select = Product::find($select_id);
        $place = Product::find($place_id);

        if (empty($select) || empty($place)) {
            return Response::json([
                'status_code' => 404,
                'message' => 'ID不存在',
            ]);
        }

        try {
            if ($move_down) {
                //下移
                $select->sort = $place->sort - 1;
                //减小最近100条记录的排序值
                DB::table('products')->where('sort', '<', $place->sort)->orderBy('sort', 'desc')->limit(100)->decrement('sort', 1);
            } else {
                //上移
                $select->sort = $place->sort + 1;
                //增大最近100条记录的排序值
                DB::table('products')->where('sort', '>', $place->sort)->orderBy('sort', 'asc')->limit(100)->increment('sort', 1);
            }

        } catch (Exception $e) {
            return Response::json([
                'status_code' => 500,
                'message' => $e->getMessage(),
            ]);
        }
        $select->save();

        return Response::json([
            'status_code' => 200,
            'message' => 'success',
        ]);
    }
}
