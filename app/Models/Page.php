<?php

namespace App\Models;

use Exception;
use Request;
use Response;


class Page extends BaseModule
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
        0 => '@page-delete',
        2 => '@page-cancel',
        9 => '@page-publish',
    ];

    protected $table = 'pages';

    protected $fillable = ['site_id','title','subtitle','author','origin','slug','summary','image_url','video_url','audio_url','images','videos','audios','content','top','member_id','user_id','sort','state','published_at'];

    protected $dates = ['published_at'];

    protected $entities = ['member_id','user_id'];

    public static function stores($input)
    {
        $input['state'] = static::STATE_NORMAL;

        $page = static::create($input);

        return $page;
    }

    public static function updates($id, $input)
    {
        $page = static::find($id);

        $page->update($input);

        return $page;
    }

    public static function table()
    {
        $filters = Request::all();

        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $ds = new DataSource();
        $pages = static::with('user')
            ->filter($filters)
            ->orderBy('sort', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $ds->total = static::filter($filters)
            ->count();

        $pages->transform(function ($page) {
            $attributes = $page->getAttributes();
            foreach ($page->entities as $entity) {
                $entity_map = str_replace('_id', '_name', $entity);
                $entity = str_replace('_id', '', $entity);
                $attributes[$entity_map] = empty($page->$entity) ? '' : $page->$entity->name;
            }
            foreach ($page->dates as $date) {
                $attributes[$date] = empty($page->$date) ? '' : $page->$date->toDateTimeString();
            }
            $attributes['state_name'] = $page->stateName();
            $attributes['created_at'] = empty($page->created_at) ? '' : $page->created_at->toDateTimeString();
            $attributes['updated_at'] = empty($page->updated_at) ? '' : $page->updated_at->toDateTimeString();
            return $attributes;
        });

        $ds->rows = $pages;

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
                self::where('sort', '<', $place->sort)
                    ->orderBy('sort', 'desc')
                    ->limit(100)
                    ->decrement('sort');
            } else {
                //上移
                $select->sort = $place->sort + 1;
                //增大最近100条记录的排序值
                self::where('sort', '>', $place->sort)
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