<?php

namespace App\Api\Controllers;

use App\Models\Payment;
use Request;

class PaymentController extends BaseController
{
    public function __construct()
    {
    }

    public function transform($payment)
    {
        $attributes = $payment->getAttributes();
        $attributes['images'] = $payment->images()->transform(function ($item) use ($payment) {
            return [
                'id' => $item->id,
                'title' => !empty($item->title) ?: $payment->title,
                'url' => get_image_url($item->url),
                'summary' => $item->summary,
            ];
        });
        $attributes['comment_count'] = $payment->comment_count;
        $attributes['favorite_count'] = $payment->favorite_count;
        $attributes['follow_count'] = $payment->follow_count;
        $attributes['like_count'] = $payment->like_count;
        $attributes['click_count'] = $payment->click_count;
        $attributes['created_at'] = empty($payment->created_at) ? '' : $payment->created_at->toDateTimeString();
        $attributes['updated_at'] = empty($payment->updated_at) ? '' : $payment->updated_at->toDateTimeString();
        return $attributes;
    }

    /**
     * @SWG\Get(
     *   path="/payments",
     *   summary="获取支付方式列表",
     *   tags={"/payments 支付方式"},
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

        $key = "payment-list-$site_id-$page_size-$page";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page) {
            $payments = Payment::with('items')
                ->where('site_id', $site_id)
                ->where('state', Payment::STATE_PUBLISHED)
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $payments->transform(function ($payment) {
                return $this->transform($payment);
            });

            return $this->responseSuccess($payments);
        });
    }

    /**
     * @SWG\Get(
     *   path="/payments/search",
     *   summary="搜索支付方式",
     *   tags={"/payments 支付方式"},
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

        $payments = Payment::with('items')
            ->where('site_id', $site_id)
            ->where('title', 'like', '%' . $title . '%')
            ->where('state', Payment::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $payments->transform(function ($payment) {
            return $this->transform($payment);
        });

        return $this->responseSuccess($payments);
    }

    /**
     * @SWG\Get(
     *   path="/payments/info",
     *   summary="获取支付方式信息",
     *   tags={"/payments 支付方式"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="支付方式ID", type="string"),
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

        Payment::click($id);

        $key = "payments-info-$id";

        return cache_remember($key, 1, function () use ($id) {
            $payment = Payment::find($id);
            if (empty($payment)) {
                return $this->responseFail('此ID不存在');
            }

            return $this->responseSuccess($this->transform($payment));
        });
    }

    /**
     * @SWG\Get(
     *   path="/payments/detail",
     *   summary="获取支付方式详情页",
     *   tags={"/payments 支付方式"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="支付方式ID", type="string"),
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

        Payment::click($id);

        $key = "payments-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $payment = Payment::findOrFail($id);
            $site = $payment->site;
            $theme = $payment->site->mobile_theme->name;
            $payment->content = replace_content_url($payment->content);
            return view("themes.$theme.payments.detail", compact('site', 'payment'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/payments/share",
     *   summary="获取支付方式分享页",
     *   tags={"/payments 支付方式"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="支付方式ID", type="string"),
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

        Payment::click($id);

        $key = "payments-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $payment = Payment::findOrFail($id);
            $site = $payment->site;
            $theme = $payment->site->mobile_theme->name;
            $payment->content = replace_content_url($payment->content);
            $share = 1;
            return view("themes.$theme.payments.detail", compact('site', 'payment', 'share'))->__toString();
        });
    }
}