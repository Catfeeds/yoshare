<?php

namespace App\Models;

use Exception;
use Request;
use Response;
use Auth;


class Article extends BaseModule
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
        0 => '@article-delete',
        2 => '@article-cancel',
        9 => '@article-publish',
    ];

    protected $table = 'articles';

    protected $fillable = ['site_id','category_id','type','title','summary','image_url','content','top','images','videos','member_id','user_id','sort','state','published_at'];

    protected $dates = ['published_at'];

    protected $entities = ['member_id','user_id'];

    public static function stores($input)
    {
        $input['state'] = static::STATE_NORMAL;

        $article = static::create($input);

        return $article;
    }

    public static function updates($id, $input)
    {
        $article = static::find($id);

        $article->update($input);

        return $article;
    }

    public static function table()
    {
        $filters = Request::all();

        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;
        $site_id = Auth::user()->site_id;

        $ds = new DataSource();
        $articles = static::with('user')
            ->where('site_id', $site_id)
            ->filter($filters)
            ->orderBy('sort', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $ds->total = static::filter($filters)
            ->count();

        $articles->transform(function ($article) {
            $attributes = $article->getAttributes();
            foreach ($article->entities as $entity) {
                $entity_map = str_replace('_id', '_name', $entity);
                $entity = str_replace('_id', '', $entity);
                $attributes[$entity_map] = empty($article->$entity) ? '' : $article->$entity->name;
            }
            foreach ($article->dates as $date) {
                $attributes[$date] = empty($article->$date) ? '' : $article->$date->toDateTimeString();
            }
            $attributes['state_name'] = $article->stateName();
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