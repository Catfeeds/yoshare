<?php

namespace App\Api\Controllers;

use App\Models\Goods;
use Request;

class GoodsController extends BaseController
{
    public function __construct()
    {
    }

    public function transform($good)
    {
        $attributes = $good->getAttributes();
        $attributes['images'] = $good->images()->transform(function ($item) use ($good) {
            return [
                'id' => $item->id,
                'title' => !empty($item->title) ?: $good->title,
                'url' => get_image_url($item->url),
                'summary' => $item->summary,
            ];
        });
        $attributes['comment_count'] = $good->comment_count;
        $attributes['favorite_count'] = $good->favorite_count;
        $attributes['follow_count'] = $good->follow_count;
        $attributes['like_count'] = $good->like_count;
        $attributes['click_count'] = $good->click_count;
        $attributes['created_at'] = empty($good->created_at) ? '' : $good->created_at->toDateTimeString();
        $attributes['updated_at'] = empty($good->updated_at) ? '' : $good->updated_at->toDateTimeString();
        return $attributes;
    }

    /**
     * @SWG\Get(
     *   path="/goods",
     *   summary="获取商品列表",
     *   tags={"/goods 商品"},
     *   @SWG\Parameter(name="site_id", in="query", required=true, description="站点ID", type="string"),
     *   @SWG\Parameter(name="category_id", in="query", required=true, description="栏目ID", type="string"),
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
        $category_id = Request::get('category_id');
        $page_size = Request::get('page_size') ? Request::get('page_size') : 20;
        $page = Request::get('page') ? Request::get('page') : 1;

        $key = "good-list-$site_id-$category_id-$page_size-$page";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page, $category_id) {
            $goods = Goods::with('items')
                ->where('site_id', $site_id)
                ->where('category_id', $category_id)
                ->where('state', Goods::STATE_PUBLISHED)
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $goods->transform(function ($good) {
                return $this->transform($good);
            });

            return $this->responseSuccess($goods);
        });
    }

    /**
     * @SWG\Get(
     *   path="/goods/search",
     *   summary="搜索商品",
     *   tags={"/goods 商品"},
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

        $goods = Goods::with('items')
            ->where('site_id', $site_id)
            ->where('title', 'like', '%' . $title . '%')
            ->where('state', Goods::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $goods->transform(function ($good) {
            return $this->transform($good);
        });

        return $this->responseSuccess($goods);
    }

    /**
     * @SWG\Get(
     *   path="/goods/info",
     *   summary="获取商品信息",
     *   tags={"/goods 商品"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="商品ID", type="string"),
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

        Goods::click($id);

        $key = "goods-info-$id";

        return cache_remember($key, 1, function () use ($id) {
            $good = Goods::find($id);
            if (empty($good)) {
                return $this->responseFail('此ID不存在');
            }

            return $this->responseSuccess($this->transform($good));
        });
    }

    /**
     * @SWG\Get(
     *   path="/goods/detail",
     *   summary="获取商品详情页",
     *   tags={"/goods 商品"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="商品ID", type="string"),
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

        Goods::click($id);

        $key = "goods-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $good = Goods::findOrFail($id);
            $site = $good->site;
            $theme = $good->site->mobile_theme->name;
            $good->content = replace_content_url($good->content);
            return view("themes.$theme.goods.detail", compact('site', 'good'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/goods/share",
     *   summary="获取商品分享页",
     *   tags={"/goods 商品"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="商品ID", type="string"),
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

        Goods::click($id);

        $key = "goods-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $good = Goods::findOrFail($id);
            $site = $good->site;
            $theme = $good->site->mobile_theme->name;
            $good->content = replace_content_url($good->content);
            $share = 1;
            return view("themes.$theme.goods.detail", compact('site', 'good', 'share'))->__toString();
        });
    }
}