<?php

namespace App\Models;

use Exception;
use Request;
use Response;


class Article extends BaseModule
{
    const MODULE_ID = 1;

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

    const STATE_PERMISSIONS = [
        0 => '@article-delete',
        2 => '@article-cancel',
        9 => '@article-publish',
    ];

    protected $table = 'articles';

    protected $fillable = [
        'site_id',
        'category_id',
        'title',
        'summary',
        'image_url',
        'content',
        'member_id',
        'user_id',
        'sort',
        'state',
    ];

    protected $dates = ['published_at'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'refer');
    }

    public static function stores($input)
    {
        $input['state'] = static::STATE_NORMAL;

        $content = static::create($input);

        return $content;
    }

    public static function updates($id, $input)
    {
        $content = static::find($id);

        $content->update($input);

        return $content;
    }

    public static function table()
    {
        $filters = Request::all();
        $category_id = $filters['category_id'];

        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $ds = new DataSource();
        $articles = static::with('user')
            ->filter($filters)
            ->where('category_id', $category_id)
            ->orderBy('sort', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $ds->total = static::filter($filters)
            ->where('category_id', $category_id)
            ->count();

        $articles->transform(function ($article) {
            $attributes = $article->getAttributes();
            $attributes['user_name'] = empty($article->user) ? '' : $article->user->name;
            $attributes['state_name'] = $article->stateName();
            $attributes['published_at'] = strtotime($article->published_at) ? $article->published_at->toDateTimeString() : '';
            $attributes['created_at'] = empty($article->created_at) ? '' : $article->created_at->toDateTimeString();
            $attributes['updated_at'] = empty($article->updated_at) ? '' : $article->updated_at->toDateTimeString();
            return $attributes;
        });

        $ds->rows = $articles;

        return Response::json($ds);
    }


    /**
     * 排序
     */
    public static function sort()
    {
        $select_id = request('select_id');
        $place_id = request('place_id');
        $move_down = request('move_down');

        $select = self::find($select_id);
        $place = self::find($place_id);

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
                self::where('category_id', $select->category_id)
                    ->where('sort', '<', $place->sort)
                    ->orderBy('sort', 'desc')
                    ->limit(100)
                    ->decrement('sort');
            } else {
                //上移
                $select->sort = $place->sort + 1;
                //增大最近100条记录的排序值
                self::where('category_id', $select->category_id)
                    ->where('sort', '>', $place->sort)
                    ->orderBy('sort', 'asc')
                    ->limit(100)
                    ->increment('sort');
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