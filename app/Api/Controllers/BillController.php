<?php

namespace App\Api\Controllers;

use App\Models\Bill;
use Request;

class BillController extends BaseController
{
    public function __construct()
    {
    }

    public function transform($bill)
    {
        $attributes = $bill->getAttributes();
        $attributes['images'] = $bill->images()->transform(function ($item) use ($bill) {
            return [
                'id' => $item->id,
                'title' => !empty($item->title) ?: $bill->title,
                'url' => get_image_url($item->url),
                'summary' => $item->summary,
            ];
        });
        $attributes['comment_count'] = $bill->comment_count;
        $attributes['favorite_count'] = $bill->favorite_count;
        $attributes['follow_count'] = $bill->follow_count;
        $attributes['like_count'] = $bill->like_count;
        $attributes['click_count'] = $bill->click_count;
        $attributes['created_at'] = empty($bill->created_at) ? '' : $bill->created_at->toDateTimeString();
        $attributes['updated_at'] = empty($bill->updated_at) ? '' : $bill->updated_at->toDateTimeString();
        return $attributes;
    }

    /**
     * @SWG\Get(
     *   path="/bills",
     *   summary="获取账单表列表",
     *   tags={"/bills 账单表"},
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

        $key = "bill-list-$site_id-$page_size-$page";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page) {
            $bills = Bill::with('items')
                ->where('site_id', $site_id)
                ->where('state', Bill::STATE_PUBLISHED)
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $bills->transform(function ($bill) {
                return $this->transform($bill);
            });

            return $this->responseSuccess($bills);
        });
    }

    /**
     * @SWG\Get(
     *   path="/bills/search",
     *   summary="搜索账单表",
     *   tags={"/bills 账单表"},
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

        $bills = Bill::with('items')
            ->where('site_id', $site_id)
            ->where('title', 'like', '%' . $title . '%')
            ->where('state', Bill::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $bills->transform(function ($bill) {
            return $this->transform($bill);
        });

        return $this->responseSuccess($bills);
    }

    /**
     * @SWG\Get(
     *   path="/bills/info",
     *   summary="获取账单表信息",
     *   tags={"/bills 账单表"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="账单表ID", type="string"),
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

        Bill::click($id);

        $key = "bills-info-$id";

        return cache_remember($key, 1, function () use ($id) {
            $bill = Bill::find($id);
            if (empty($bill)) {
                return $this->responseFail('此ID不存在');
            }

            return $this->responseSuccess($this->transform($bill));
        });
    }

    /**
     * @SWG\Get(
     *   path="/bills/detail",
     *   summary="获取账单表详情页",
     *   tags={"/bills 账单表"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="账单表ID", type="string"),
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

        Bill::click($id);

        $key = "bills-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $bill = Bill::findOrFail($id);
            $site = $bill->site;
            $theme = $bill->site->mobile_theme->name;
            $bill->content = replace_content_url($bill->content);
            return view("themes.$theme.bills.detail", compact('site', 'bill'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/bills/share",
     *   summary="获取账单表分享页",
     *   tags={"/bills 账单表"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="账单表ID", type="string"),
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

        Bill::click($id);

        $key = "bills-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $bill = Bill::findOrFail($id);
            $site = $bill->site;
            $theme = $bill->site->mobile_theme->name;
            $bill->content = replace_content_url($bill->content);
            $share = 1;
            return view("themes.$theme.bills.detail", compact('site', 'bill', 'share'))->__toString();
        });
    }
}