<?php

namespace App\Api\Controllers;

use App\Models\Cart;
use Request;

class CartController extends BaseController
{
    public function __construct()
    {
    }

    public function transform($cart)
    {
        $attributes = $cart->getAttributes();
        $attributes['images'] = $cart->images()->transform(function ($item) use ($cart) {
            return [
                'id' => $item->id,
                'title' => !empty($item->title) ?: $cart->title,
                'url' => get_image_url($item->url),
                'summary' => $item->summary,
            ];
        });
        $attributes['comment_count'] = $cart->comment_count;
        $attributes['favorite_count'] = $cart->favorite_count;
        $attributes['follow_count'] = $cart->follow_count;
        $attributes['like_count'] = $cart->like_count;
        $attributes['click_count'] = $cart->click_count;
        $attributes['created_at'] = empty($cart->created_at) ? '' : $cart->created_at->toDateTimeString();
        $attributes['updated_at'] = empty($cart->updated_at) ? '' : $cart->updated_at->toDateTimeString();
        return $attributes;
    }

    /**
     * @SWG\Get(
     *   path="/carts",
     *   summary="获取购物车列表",
     *   tags={"/carts 购物车"},
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

        $key = "cart-list-$site_id-$page_size-$page";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page) {
            $carts = Cart::with('items')
                ->where('site_id', $site_id)
                ->where('state', Cart::STATE_PUBLISHED)
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $carts->transform(function ($cart) {
                return $this->transform($cart);
            });

            return $this->responseSuccess($carts);
        });
    }

    /**
     * @SWG\Get(
     *   path="/carts/search",
     *   summary="搜索购物车",
     *   tags={"/carts 购物车"},
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

        $carts = Cart::with('items')
            ->where('site_id', $site_id)
            ->where('title', 'like', '%' . $title . '%')
            ->where('state', Cart::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $carts->transform(function ($cart) {
            return $this->transform($cart);
        });

        return $this->responseSuccess($carts);
    }

    /**
     * @SWG\Get(
     *   path="/carts/info",
     *   summary="获取购物车信息",
     *   tags={"/carts 购物车"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="购物车ID", type="string"),
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

        Cart::click($id);

        $key = "carts-info-$id";

        return cache_remember($key, 1, function () use ($id) {
            $cart = Cart::find($id);
            if (empty($cart)) {
                return $this->responseFail('此ID不存在');
            }

            return $this->responseSuccess($this->transform($cart));
        });
    }

    /**
     * @SWG\Get(
     *   path="/carts/detail",
     *   summary="获取购物车详情页",
     *   tags={"/carts 购物车"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="购物车ID", type="string"),
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

        Cart::click($id);

        $key = "carts-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $cart = Cart::findOrFail($id);
            $site = $cart->site;
            $theme = $cart->site->mobile_theme->name;
            $cart->content = replace_content_url($cart->content);
            return view("themes.$theme.carts.detail", compact('site', 'cart'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/carts/share",
     *   summary="获取购物车分享页",
     *   tags={"/carts 购物车"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="购物车ID", type="string"),
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

        Cart::click($id);

        $key = "carts-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $cart = Cart::findOrFail($id);
            $site = $cart->site;
            $theme = $cart->site->mobile_theme->name;
            $cart->content = replace_content_url($cart->content);
            $share = 1;
            return view("themes.$theme.carts.detail", compact('site', 'cart', 'share'))->__toString();
        });
    }
}