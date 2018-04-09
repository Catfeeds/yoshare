<?php

namespace App\Models;

use Exception;
use Request;
use Response;


class Order extends BaseModule
{
    const STATE_DELETED = 0;
    const STATE_NOPAY = 1;
    const STATE_PAID = 2;
    const STATE_SENDED = 3;
    const STATE_CLOSED = 4;
    const STATE_RETURN = 5;
    const STATE_SUCCESS = 6;
    const STATE_REFUND = 7;

    const STATES = [
        0 => '已删除',
        1 => '未付款',
        2 => '已付款，待发货',
        3 => '已发货',
        4 => '交易关闭',
        5 => '待归还',
        6 => '交易成功',
        7 => '退款中',
    ];

    const STATE_PERMISSIONS = [
        0 => '@order-delete',
        2 => '@order-cancel',
        9 => '@order-publish',
    ];

    protected $table = 'orders';

    protected $fillable = ['site_id', 'order_num', 'member_id', 'name', 'address', 'phone', 'ship_id', 'ship_num', 'back_ship_num', 'pay_id', 'total_pay', 'total_price', 'ship_price', 'state', 'note', 'paid_at', 'shipped_at', 'finished_at'];

    protected $dates = ['paid_at', 'shipped_at', 'finished_at'];

    protected $entities = ['member_id'];

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

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
        $input['state'] = static::STATE_NOPAY;

        $order = static::create($input);

        //保存图片集
        if (isset($input['images'])) {
            Item::sync(Item::TYPE_IMAGE, $order, $input['images']);

        }

        //保存音频集
        if (isset($input['audios'])) {
            Item::sync(Item::TYPE_AUDIO, $order, $input['audios']);
        }

        //保存视频集
        if (isset($input['videos'])) {
            Item::sync(Item::TYPE_VIDEO, $order, $input['videos']);
        }

        //保存标签
        if (isset($input['tags'])) {
            Tag::sync($order, $input['tags']);
        }

        return $order;
    }

    public static function updates($id, $input)
    {
        $order = static::find($id);

        $order->update($input);

        //保存图片集
        if (isset($input['images'])) {
            Item::sync(Item::TYPE_IMAGE, $order, $input['images']);

        }

        //保存音频集
        if (isset($input['audios'])) {
            Item::sync(Item::TYPE_AUDIO, $order, $input['audios']);
        }

        //保存视频集
        if (isset($input['videos'])) {
            Item::sync(Item::TYPE_VIDEO, $order, $input['videos']);
        }

        //保存标签
        if (isset($input['tags'])) {
            Tag::sync($order, $input['tags']);
        }

        return $order;
    }

    public static function table()
    {
        $filters = Request::all();

        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $ds = new DataSource();

        $orders = static::with('member')
            ->filter($filters)
            ->skip($offset)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        $ds->total = static::filter($filters)
            ->count();

        $orders->transform(function ($order) {
            $attributes = $order->getAttributes();

            //实体类型
            foreach ($order->entities as $entity) {
                $entity_map = str_replace('_id', '_name', $entity);
                $entity = str_replace('_id', '', $entity);
                $attributes[$entity_map] = empty($order->$entity) ? '' : $order->$entity->name;
            }

            $cart = $order->cart()->first();
            if($cart){
                $goods_id = $cart->goods_id;
                $goods = Goods::find($goods_id);
                $note = $goods->name;
            }else{
                $note = '';
            }

            //日期类型
            foreach ($order->dates as $date) {
                $attributes[$date] = empty($order->$date) ? '' : $order->$date->toDateTimeString();
            }
            $attributes['tags'] = implode(',', $order->tags()->pluck('name')->toArray());
            $attributes['note'] = $note;
            $attributes['member_name'] = $order->member->username;
            $attributes['state_name'] = $order->stateName();
            $attributes['created_at'] = empty($order->created_at) ? '' : $order->created_at->toDateTimeString();
            $attributes['updated_at'] = empty($order->updated_at) ? '' : $order->updated_at->toDateTimeString();
            return $attributes;
        });

        $ds->rows = $orders;

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