<?php

namespace App\Api\Controllers;

use App\Models\Ship;
use Request;

class ShipController extends BaseController
{
    public function __construct()
    {
    }

    public function transform($ship)
    {
        $attributes = $ship->getAttributes();
        $attributes['images'] = $ship->images()->transform(function ($item) use ($ship) {
            return [
                'id' => $item->id,
                'title' => !empty($item->title) ?: $ship->title,
                'url' => get_image_url($item->url),
                'summary' => $item->summary,
            ];
        });
        $attributes['comment_count'] = $ship->comment_count;
        $attributes['favorite_count'] = $ship->favorite_count;
        $attributes['follow_count'] = $ship->follow_count;
        $attributes['like_count'] = $ship->like_count;
        $attributes['click_count'] = $ship->click_count;
        $attributes['created_at'] = empty($ship->created_at) ? '' : $ship->created_at->toDateTimeString();
        $attributes['updated_at'] = empty($ship->updated_at) ? '' : $ship->updated_at->toDateTimeString();
        return $attributes;
    }

    /**
     * @SWG\Get(
     *   path="/ships",
     *   summary="获取物流方式列表",
     *   tags={"/ships 物流方式"},
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

        $key = "ship-list-$site_id-$page_size-$page";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page) {
            $ships = Ship::with('items')
                ->where('site_id', $site_id)
                ->where('state', Ship::STATE_PUBLISHED)
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $ships->transform(function ($ship) {
                return $this->transform($ship);
            });

            return $this->responseSuccess($ships);
        });
    }

    /**
     * @SWG\Get(
     *   path="/ships/search",
     *   summary="搜索物流方式",
     *   tags={"/ships 物流方式"},
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

        $ships = Ship::with('items')
            ->where('site_id', $site_id)
            ->where('title', 'like', '%' . $title . '%')
            ->where('state', Ship::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $ships->transform(function ($ship) {
            return $this->transform($ship);
        });

        return $this->responseSuccess($ships);
    }

    /**
     * @SWG\Get(
     *   path="/ships/info",
     *   summary="获取物流方式信息",
     *   tags={"/ships 物流方式"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="物流方式ID", type="string"),
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

        Ship::click($id);

        $key = "ships-info-$id";

        return cache_remember($key, 1, function () use ($id) {
            $ship = Ship::find($id);
            if (empty($ship)) {
                return $this->responseFail('此ID不存在');
            }

            return $this->responseSuccess($this->transform($ship));
        });
    }

    /**
     * @SWG\Get(
     *   path="/ships/detail",
     *   summary="获取物流方式详情页",
     *   tags={"/ships 物流方式"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="物流方式ID", type="string"),
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

        Ship::click($id);

        $key = "ships-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $ship = Ship::findOrFail($id);
            $site = $ship->site;
            $theme = $ship->site->mobile_theme->name;
            $ship->content = replace_content_url($ship->content);
            return view("themes.$theme.ships.detail", compact('site', 'ship'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/ships/share",
     *   summary="获取物流方式分享页",
     *   tags={"/ships 物流方式"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="物流方式ID", type="string"),
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

        Ship::click($id);

        $key = "ships-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $ship = Ship::findOrFail($id);
            $site = $ship->site;
            $theme = $ship->site->mobile_theme->name;
            $ship->content = replace_content_url($ship->content);
            $share = 1;
            return view("themes.$theme.ships.detail", compact('site', 'ship', 'share'))->__toString();
        });
    }
}