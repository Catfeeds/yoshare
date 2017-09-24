<?php

namespace App\Models;

use Exception;
use Request;
use Response;
use Carbon\Carbon;

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

    protected $fillable = ['site_id', 'category_id', 'type', 'title', 'subtitle', 'link_type', 'link', 'author', 'origin', 'keywords', 'summary', 'image_url', 'video_url', 'images', 'videos', 'content', 'top', 'member_id', 'user_id', 'sort', 'state', 'published_at'];

    protected $dates = ['published_at'];

    protected $entities = ['member_id', 'user_id'];

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

    public static function tree($state = '', $parent_id = 0, $module_id = 0, $show_parent = true)
    {
        $categories = Category::owns()
            ->where(function ($query) use ($state) {
                if (!empty($state)) {
                    $query->where('state', $state);
                }
            })
            ->where(function ($query) use ($module_id) {
                if (!empty($module_id)) {
                    $query->where('module_id', $module_id);
                }
            })
            ->orderBy('sort')
            ->get();

        $parents = Category::where('module_id', $module_id)
            ->where('type', Category::TYPE_FEATURE)
            ->orderBy('created_at', 'desc')
            ->get();
        $arr = [];
        foreach ($parents as $parent) {
            if (empty($parent)) {
                $root = new Node();
                $root->text = '所有栏目';
            } else {
                $root = new Node();
                $root->id = 0;
                $root->time = date('Ym', strtotime($parent->created_at));
                $root->text = date('Ym', strtotime($parent->created_at));
            }
            static::getNodes($root, $categories);

            if(in_array($root, $arr) == false){
                $arr[] = $root;
            }

        }

        if ($show_parent) {
            return $arr;
        } else {
            return $root->nodes;
        }

    }

    public static function getNodes($parent, $categories)
    {
        foreach ($categories as $category) {
            $time = date('Ym', strtotime($category->created_at));

            if ($time == $parent->text) {
                $node = new Node();
                $node->id = $category->id;
                $node->text = $category->name;
                $node->time = date('Ymd', strtotime($category->created_at));

                $parent->nodes[] = $node;
                static::getNodes($node, $categories);
            }
        }
    }
}