<?php

namespace App\Models;

use Exception;
use Request;
use Response;


class Bill extends BaseModule
{
    const SITE_ID = 1;
    const STATE_DELETED = 0;
    const STATE_NORMAL = 1;
    const STATE_REFUND = 2;

    const TYPE_ORDER = 0;
    const TYPE_DEPOSIT = 1;
    const TYPE_BALANCE = 2;
    const TYPE_REFUND = 3;

    const TYPES = [
        'yoshare_order'     => 0,
        'yoshare_deposit'   => 1,
        'yoshare_balance'   => 2,
        'yoshare_refund'    => 3,
    ];

    const STATES = [
        0 => '已删除',
        1 => '已充值',
        2 => '已退款',
    ];


    const STATE_PERMISSIONS = [
        0 => '@bill-delete',
        2 => '@bill-cancel',
        9 => '@bill-publish',
    ];

    protected $table = 'bills';

    protected $fillable = ['site_id', 'member_id', 'bill_num', 'type', 'money', 'note', 'state'];

    protected $dates = [];

    protected $entities = ['member_id'];

    public function typeName()
    {
        switch ($this->type) {
            case static::TYPE_ORDER:
                return '订单支付';
                break;
            case static::TYPE_DEPOSIT:
                return '押金充值';
                break;
            case static::TYPE_BALANCE:
                return '余额充值';
                break;
            case static::TYPE_REFUND:
                return '押金退款';
                break;
        }
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
        $input['site_id'] = static::SITE_ID;

        $bill = static::create($input);

        //保存图片集
        if (isset($input['images'])) {
            Item::sync(Item::TYPE_IMAGE, $bill, $input['images']);

        }

        //保存音频集
        if (isset($input['audios'])) {
            Item::sync(Item::TYPE_AUDIO, $bill, $input['audios']);
        }

        //保存视频集
        if (isset($input['videos'])) {
            Item::sync(Item::TYPE_VIDEO, $bill, $input['videos']);
        }

        //保存标签
        if (isset($input['tags'])) {
            Tag::sync($bill, $input['tags']);
        }

        return $bill;
    }

    public static function updates($id, $input)
    {
        $bill = static::find($id);

        $bill->update($input);

        //保存图片集
        if (isset($input['images'])) {
            Item::sync(Item::TYPE_IMAGE, $bill, $input['images']);

        }

        //保存音频集
        if (isset($input['audios'])) {
            Item::sync(Item::TYPE_AUDIO, $bill, $input['audios']);
        }

        //保存视频集
        if (isset($input['videos'])) {
            Item::sync(Item::TYPE_VIDEO, $bill, $input['videos']);
        }

        //保存标签
        if (isset($input['tags'])) {
            Tag::sync($bill, $input['tags']);
        }

        return $bill;
    }

    public static function table()
    {
        $filters = Request::all();

        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $ds = new DataSource();
        $bills = static::with('member')
            ->filter($filters)
            ->skip($offset)
            ->limit($limit)
            ->get();

        $ds->total = static::filter($filters)
            ->count();

        $bills->transform(function ($bill) {
            $attributes = $bill->getAttributes();

            //实体类型
            foreach ($bill->entities as $entity) {
                $entity_map = str_replace('_id', '_name', $entity);
                $entity = str_replace('_id', '', $entity);
                $attributes[$entity_map] = empty($bill->$entity) ? '' : $bill->$entity->name;
            }

            //日期类型
            foreach ($bill->dates as $date) {
                $attributes[$date] = empty($bill->$date) ? '' : $bill->$date->toDateTimeString();
            }
            $attributes['tags'] = implode(',', $bill->tags()->pluck('name')->toArray());
            $attributes['type'] = $bill->typeName();
            $attributes['member_id'] = $bill->member->username;
            $attributes['state_name'] = $bill->stateName();
            $attributes['created_at'] = empty($bill->created_at) ? '' : $bill->created_at->toDateTimeString();
            $attributes['updated_at'] = empty($bill->updated_at) ? '' : $bill->updated_at->toDateTimeString();
            return $attributes;
        });

        $ds->rows = $bills;

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