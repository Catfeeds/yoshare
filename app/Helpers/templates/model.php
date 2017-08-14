<?php

namespace App\Models;

use Exception;
use Request;
use Response;


class __model__ extends BaseModule
{
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
        0 => '@__permission__-delete',
        2 => '@__permission__-cancel',
        9 => '@__permission__-publish',
    ];

    protected $table = '__table_name__';

    protected $fillable = [__fillable__];

    protected $dates = [__dates__];

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
        $items = static::with('user')
            ->filter($filters)
            ->where('category_id', $category_id)
            ->orderBy('sort', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $ds->total = static::filter($filters)
            ->where('category_id', $category_id)
            ->count();

        $items->transform(function ($item) {
            $attributes = $item->getAttributes();
            $attributes['user_name'] = empty($item->user) ? '' : $item->user->name;
            $attributes['state_name'] = $item->stateName();
            $attributes['published_at'] = strtotime($item->published_at) ? $item->published_at->toDateTimeString() : '';
            $attributes['created_at'] = empty($item->created_at) ? '' : $item->created_at->toDateTimeString();
            $attributes['updated_at'] = empty($item->updated_at) ? '' : $item->updated_at->toDateTimeString();
            return $attributes;
        });

        $ds->rows = $items;

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