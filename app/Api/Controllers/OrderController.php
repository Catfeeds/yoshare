<?php

namespace App\Api\Controllers;

use App\Models\Order;
use Request;

class OrderController extends BaseController
{
    public function __construct()
    {
    }

    public function transform($order)
    {
        $attributes = $order->getAttributes();
        $attributes['images'] = $order->images()->transform(function ($item) use ($order) {
            return [
                'id' => $item->id,
                'title' => !empty($item->title) ?: $order->title,
                'url' => get_image_url($item->url),
                'summary' => $item->summary,
            ];
        });
        $attributes['comment_count'] = $order->comment_count;
        $attributes['favorite_count'] = $order->favorite_count;
        $attributes['follow_count'] = $order->follow_count;
        $attributes['like_count'] = $order->like_count;
        $attributes['click_count'] = $order->click_count;
        $attributes['created_at'] = empty($order->created_at) ? '' : $order->created_at->toDateTimeString();
        $attributes['updated_at'] = empty($order->updated_at) ? '' : $order->updated_at->toDateTimeString();
        return $attributes;
    }

    /**
     * @SWG\Get(
     *   path="/orders",
     *   summary="获取订单列表",
     *   tags={"/orders 订单"},
     *   @SWG\Parameter(name="site_id", in="query", required=true, description="站点ID", type="string"),
     *   @SWG\Parameter(name="page_size", in="query", required=true, description="分页大小", type="integer"),
     *   @SWG\Parameter(name="page", in="query", required=true, description="分页序号", type="integer"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   )
     * )
     */
    public function lists()
    {
        $site_id = Request::get('site_id') ? Request::get('site_id') : 1;
        $page_size = Request::get('page_size') ? Request::get('page_size') : 20;
        $page = Request::get('page') ? Request::get('page') : 1;

        $key = "order-list-$site_id-$page_size-$page";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page) {
            $orders = Order::with('items')
                ->where('site_id', $site_id)
                ->where('state', Order::STATE_PUBLISHED)
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $orders->transform(function ($order) {
                return $this->transform($order);
            });

            return $this->responseSuccess($orders);
        });
    }

    /**
     * @SWG\Get(
     *   path="/orders/search",
     *   summary="搜索订单",
     *   tags={"/orders 订单"},
     *   @SWG\Parameter(name="site_id", in="query", required=true, description="站点ID", type="string"),
     *   @SWG\Parameter(name="title", in="query", required=true, description="搜索标题", type="string"),
     *   @SWG\Parameter(name="page_size", in="query", required=true, description="分页大小", type="integer"),
     *   @SWG\Parameter(name="page", in="query", required=true, description="分页序号", type="integer"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   )
     * )
     */
    public function search()
    {
        $site_id = Request::get('site_id') ? Request::get('site_id') : 1;
        $page_size = Request::get('page_size') ? Request::get('page_size') : 20;
        $page = Request::get('page') ? Request::get('page') : 1;
        $title = Request::get('title');

        $orders = Order::with('items')
            ->where('site_id', $site_id)
            ->where('title', 'like', '%' . $title . '%')
            ->where('state', Order::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $orders->transform(function ($order) {
            return $this->transform($order);
        });

        return $this->responseSuccess($orders);
    }

    /**
     * @SWG\Get(
     *   path="/orders/info",
     *   summary="获取订单信息",
     *   tags={"/orders 订单"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="订单ID", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   )
     * )
     */
    public function info()
    {
        $id = Request::get('id');

        Order::click($id);

        $key = "orders-info-$id";

        return cache_remember($key, 1, function () use ($id) {
            $order = Order::find($id);
            if (empty($order)) {
                return $this->responseFail('此ID不存在');
            }

            return $this->responseSuccess($this->transform($order));
        });
    }

    /**
     * @SWG\Get(
     *   path="/orders/detail",
     *   summary="获取订单详情页",
     *   tags={"/orders 订单"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="订单ID", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   )
     * )
     */
    public function detail()
    {
        $id = Request::get('id');

        Order::click($id);

        $key = "orders-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $order = Order::findOrFail($id);
            $site = $order->site;
            $theme = $order->site->mobile_theme->name;
            $order->content = replace_content_url($order->content);
            return view("themes.$theme.orders.detail", compact('site', 'order'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/orders/share",
     *   summary="获取订单分享页",
     *   tags={"/orders 订单"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="订单ID", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   )
     * )
     */
    public function share()
    {
        $id = Request::get('id');

        Order::click($id);

        $key = "orders-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $order = Order::findOrFail($id);
            $site = $order->site;
            $theme = $order->site->mobile_theme->name;
            $order->content = replace_content_url($order->content);
            $share = 1;
            return view("themes.$theme.orders.detail", compact('site', 'order', 'share'))->__toString();
        });
    }
}