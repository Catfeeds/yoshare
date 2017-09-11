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

    protected $table = '__table__';

    protected $fillable = [__fillable__];

    protected $dates = [__dates__];

    protected $entities = [__entities__];

    public static function stores($input)
    {
        $input['state'] = static::STATE_NORMAL;

        $__singular__ = static::create($input);

        return $__singular__;
    }

    public static function updates($id, $input)
    {
        $__singular__ = static::find($id);

        $__singular__->update($input);

        return $__singular__;
    }

    public function files()
    {
        return $this->morphMany(File::class, 'refer');
    }

    public static function table()
    {
        $filters = Request::all();

        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $ds = new DataSource();
        $__plural__ = static::with('user')
            ->filter($filters)
            ->orderBy('sort', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $ds->total = static::filter($filters)
            ->count();

        $__plural__->transform(function ($__singular__) {
            $attributes = $__singular__->getAttributes();
            foreach ($__singular__->entities as $entity) {
                $entity_map = str_replace('_id', '_name', $entity);
                $entity = str_replace('_id', '', $entity);
                $attributes[$entity_map] = empty($__singular__->$entity) ? '' : $__singular__->$entity->name;
            }
            foreach ($__singular__->dates as $date) {
                $attributes[$date] = empty($__singular__->$date) ? '' : $__singular__->$date->toDateTimeString();
            }
            $attributes['state_name'] = $__singular__->stateName();
            $attributes['created_at'] = empty($__singular__->created_at) ? '' : $__singular__->created_at->toDateTimeString();
            $attributes['updated_at'] = empty($__singular__->updated_at) ? '' : $__singular__->updated_at->toDateTimeString();
            return $attributes;
        });

        $ds->rows = $__plural__;

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