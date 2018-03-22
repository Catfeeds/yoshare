<?php

namespace App\Models;

use Exception;
use Request;
use Response;


class Cart extends BaseModule
{
    const STATE_DELETED = 0;
    const STATE_NORMAL = 1;
    const STATE_CANCELED = 2;
    const STATE_PUBLISHED = 9;

    const ORDER_ID_NO = 0;

    const STATES = [
        0 => '已删除',
        1 => '未发布',
        2 => '已撤回',
        9 => '已发布',
    ];

    const STATE_PERMISSIONS = [
        0 => '@cart-delete',
        2 => '@cart-cancel',
        9 => '@cart-publish',
    ];

    protected $table = 'carts';

    protected $fillable = ['site_id', 'goods_id', 'price', 'number', 'order_id', 'member_id', 'state'];

    protected $dates = [];

    protected $entities = ['member_id'];

    public function previous()
    {
        return static::where('site_id', $this->site_id)
            ->where('state', $this->state)
            ->where('sort', '>', $this->sort)
            ->orderBy('sort', 'desc')
            ->first();
    }

    public function next()
    {
        return static::where('site_id', $this->site_id)
            ->where('state', $this->state)
            ->where('sort', '<', $this->sort)
            ->orderBy('sort', 'desc')
            ->first();
    }

    public static function stores($input)
    {
        $input['state'] = static::STATE_NORMAL;

        $cart = static::create($input);

        //保存图片集
        if (isset($input['images'])) {
            Item::sync(Item::TYPE_IMAGE, $cart, $input['images']);

        }

        //保存音频集
        if (isset($input['audios'])) {
            Item::sync(Item::TYPE_AUDIO, $cart, $input['audios']);
        }

        //保存视频集
        if (isset($input['videos'])) {
            Item::sync(Item::TYPE_VIDEO, $cart, $input['videos']);
        }

        //保存标签
        if (isset($input['tags'])) {
            Tag::sync($cart, $input['tags']);
        }

        return $cart;
    }

    public static function updates($id, $input)
    {
        $cart = static::find($id);

        $cart->update($input);

        //保存图片集
        if (isset($input['images'])) {
            Item::sync(Item::TYPE_IMAGE, $cart, $input['images']);

        }

        //保存音频集
        if (isset($input['audios'])) {
            Item::sync(Item::TYPE_AUDIO, $cart, $input['audios']);
        }

        //保存视频集
        if (isset($input['videos'])) {
            Item::sync(Item::TYPE_VIDEO, $cart, $input['videos']);
        }

        //保存标签
        if (isset($input['tags'])) {
            Tag::sync($cart, $input['tags']);
        }

        return $cart;
    }

    public static function table()
    {
        $filters = Request::all();

        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $ds = new DataSource();
        $carts = static::with('user')
            ->filter($filters)
            ->orderBy('top', 'desc')
            ->orderBy('sort', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $ds->total = static::filter($filters)
            ->count();

        $carts->transform(function ($cart) {
            $attributes = $cart->getAttributes();

            //实体类型
            foreach ($cart->entities as $entity) {
                $entity_map = str_replace('_id', '_name', $entity);
                $entity = str_replace('_id', '', $entity);
                $attributes[$entity_map] = empty($cart->$entity) ? '' : $cart->$entity->name;
            }

            //日期类型
            foreach ($cart->dates as $date) {
                $attributes[$date] = empty($cart->$date) ? '' : $cart->$date->toDateTimeString();
            }
            $attributes['tags'] = implode(',', $cart->tags()->pluck('name')->toArray());
            $attributes['state_name'] = $cart->stateName();
            $attributes['created_at'] = empty($cart->created_at) ? '' : $cart->created_at->toDateTimeString();
            $attributes['updated_at'] = empty($cart->updated_at) ? '' : $cart->updated_at->toDateTimeString();
            return $attributes;
        });

        $ds->rows = $carts;

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

        if ($select->top && !$place->top) {
            return Response::json([
                'status_code' => 404,
                'message' => '置顶记录不允许移至普通位置',
            ]);
        }

        if (!$select->top && $place->top) {
            return Response::json([
                'status_code' => 404,
                'message' => '普通记录不允许移至置顶位置',
            ]);
        }

        $sort = $place->sort;
        try {
            if ($move_down) {
                //下移
                //增加移动区间的排序值
                self::owns()
                    ->where('sort', '>=', $place->sort)
                    ->where('sort', '<', $select->sort)
                    ->increment('sort');
            } else {
                //上移
                //减少移动区间的排序值
                self::owns()
                    ->where('sort', '>', $select->sort)
                    ->where('sort', '<=', $place->sort)
                    ->decrement('sort');
            }
        } catch (Exception $e) {
            return Response::json([
                'status_code' => 500,
                'message' => $e->getMessage(),
            ]);
        }
        $select->sort = $sort;
        $select->save();

        return Response::json([
            'status_code' => 200,
            'message' => 'success',
        ]);
    }
}