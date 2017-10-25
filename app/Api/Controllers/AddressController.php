<?php

namespace App\Api\Controllers;

use App\Models\Address;
use Request;

class AddressController extends BaseController
{
    public function __construct()
    {
    }

    public function transform($address)
    {
        $attributes = $address->getAttributes();
        $attributes['images'] = $address->images()->transform(function ($item) use ($address) {
            return [
                'id' => $item->id,
                'title' => !empty($item->title) ?: $address->title,
                'url' => get_image_url($item->url),
                'summary' => $item->summary,
            ];
        });
        $attributes['comment_count'] = $address->comment_count;
        $attributes['favorite_count'] = $address->favorite_count;
        $attributes['follow_count'] = $address->follow_count;
        $attributes['like_count'] = $address->like_count;
        $attributes['click_count'] = $address->click_count;
        $attributes['created_at'] = empty($address->created_at) ? '' : $address->created_at->toDateTimeString();
        $attributes['updated_at'] = empty($address->updated_at) ? '' : $address->updated_at->toDateTimeString();
        return $attributes;
    }

    /**
     * @SWG\Get(
     *   path="/addresses",
     *   summary="获取地址列表",
     *   tags={"/addresses 地址"},
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

        $key = "address-list-$site_id-$page_size-$page";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page) {
            $addresses = Address::with('items')
                ->where('site_id', $site_id)
                ->where('state', Address::STATE_PUBLISHED)
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $addresses->transform(function ($address) {
                return $this->transform($address);
            });

            return $this->responseSuccess($addresses);
        });
    }

    /**
     * @SWG\Get(
     *   path="/addresses/search",
     *   summary="搜索地址",
     *   tags={"/addresses 地址"},
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

        $addresses = Address::with('items')
            ->where('site_id', $site_id)
            ->where('title', 'like', '%' . $title . '%')
            ->where('state', Address::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $addresses->transform(function ($address) {
            return $this->transform($address);
        });

        return $this->responseSuccess($addresses);
    }

    /**
     * @SWG\Get(
     *   path="/addresses/info",
     *   summary="获取地址信息",
     *   tags={"/addresses 地址"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="地址ID", type="string"),
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

        Address::click($id);

        $key = "addresses-info-$id";

        return cache_remember($key, 1, function () use ($id) {
            $address = Address::find($id);
            if (empty($address)) {
                return $this->responseFail('此ID不存在');
            }

            return $this->responseSuccess($this->transform($address));
        });
    }

    /**
     * @SWG\Get(
     *   path="/addresses/detail",
     *   summary="获取地址详情页",
     *   tags={"/addresses 地址"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="地址ID", type="string"),
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

        Address::click($id);

        $key = "addresses-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $address = Address::findOrFail($id);
            $site = $address->site;
            $theme = $address->site->mobile_theme->name;
            $address->content = replace_content_url($address->content);
            return view("themes.$theme.addresses.detail", compact('site', 'address'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/addresses/share",
     *   summary="获取地址分享页",
     *   tags={"/addresses 地址"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="地址ID", type="string"),
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

        Address::click($id);

        $key = "addresses-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $address = Address::findOrFail($id);
            $site = $address->site;
            $theme = $address->site->mobile_theme->name;
            $address->content = replace_content_url($address->content);
            $share = 1;
            return view("themes.$theme.addresses.detail", compact('site', 'address', 'share'))->__toString();
        });
    }
}