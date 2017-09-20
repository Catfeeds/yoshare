<?php

namespace App\Models;

use Exception;
use Request;
use Response;


class Feature extends BaseModule
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
        0 => '@feature-delete',
        2 => '@feature-cancel',
        9 => '@feature-publish',
    ];

    protected $table = 'features';

    protected $fillable = ['category_id','type','title','summary','image_url','content','top','published_at','images','videos','member_id','user_id','sort','state','site_id'];

    protected $dates = ['published_at'];

    protected $entities = ['member_id','user_id'];

    public static function stores($input)
    {
        $input['state'] = static::STATE_NORMAL;

        $feature = static::create($input);

        return $feature;
    }

    public static function updates($id, $input)
    {
        $feature = static::find($id);

        $feature->update($input);

        return $feature;
    }

    public static function table()
    {
        $filters = Request::all();

        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $ds = new DataSource();
        $features = static::with('user')
            ->filter($filters)
            ->orderBy('sort', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $ds->total = static::filter($filters)
            ->count();

        $features->transform(function ($feature) {
            $attributes = $feature->getAttributes();
            foreach ($feature->entities as $entity) {
                $entity_map = str_replace('_id', '_name', $entity);
                $entity = str_replace('_id', '', $entity);
                $attributes[$entity_map] = empty($feature->$entity) ? '' : $feature->$entity->name;
            }
            foreach ($feature->dates as $date) {
                $attributes[$date] = empty($feature->$date) ? '' : $feature->$date->toDateTimeString();
            }
            $attributes['state_name'] = $feature->stateName();
            $attributes['created_at'] = empty($feature->created_at) ? '' : $feature->created_at->toDateTimeString();
            $attributes['updated_at'] = empty($feature->updated_at) ? '' : $feature->updated_at->toDateTimeString();
            return $attributes;
        });

        $ds->rows = $features;

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