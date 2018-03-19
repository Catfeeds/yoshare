<?php

namespace App\Api\Controllers;

use App\Models\Wallet;
use Request;

class WalletController extends BaseController
{
    public function __construct()
    {
    }

    public function transform($wallet)
    {
        $attributes = $wallet->getAttributes();
        $attributes['images'] = $wallet->images()->transform(function ($item) use ($wallet) {
            return [
                'id' => $item->id,
                'title' => !empty($item->title) ?: $wallet->title,
                'url' => get_image_url($item->url),
                'summary' => $item->summary,
            ];
        });
        $attributes['comment_count'] = $wallet->comment_count;
        $attributes['favorite_count'] = $wallet->favorite_count;
        $attributes['follow_count'] = $wallet->follow_count;
        $attributes['like_count'] = $wallet->like_count;
        $attributes['click_count'] = $wallet->click_count;
        $attributes['created_at'] = empty($wallet->created_at) ? '' : $wallet->created_at->toDateTimeString();
        $attributes['updated_at'] = empty($wallet->updated_at) ? '' : $wallet->updated_at->toDateTimeString();
        return $attributes;
    }

    /**
     * @SWG\Get(
     *   path="/wallets",
     *   summary="获取钱包列表",
     *   tags={"/wallets 钱包"},
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

        $key = "wallet-list-$site_id-$page_size-$page";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page) {
            $wallets = Wallet::with('items')
                ->where('site_id', $site_id)
                ->where('state', Wallet::STATE_PUBLISHED)
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $wallets->transform(function ($wallet) {
                return $this->transform($wallet);
            });

            return $this->responseSuccess($wallets);
        });
    }

    /**
     * @SWG\Get(
     *   path="/wallets/search",
     *   summary="搜索钱包",
     *   tags={"/wallets 钱包"},
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

        $wallets = Wallet::with('items')
            ->where('site_id', $site_id)
            ->where('title', 'like', '%' . $title . '%')
            ->where('state', Wallet::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $wallets->transform(function ($wallet) {
            return $this->transform($wallet);
        });

        return $this->responseSuccess($wallets);
    }

    /**
     * @SWG\Get(
     *   path="/wallets/info",
     *   summary="获取钱包信息",
     *   tags={"/wallets 钱包"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="钱包ID", type="string"),
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

        Wallet::click($id);

        $key = "wallets-info-$id";

        return cache_remember($key, 1, function () use ($id) {
            $wallet = Wallet::find($id);
            if (empty($wallet)) {
                return $this->responseFail('此ID不存在');
            }

            return $this->responseSuccess($this->transform($wallet));
        });
    }

    /**
     * @SWG\Get(
     *   path="/wallets/detail",
     *   summary="获取钱包详情页",
     *   tags={"/wallets 钱包"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="钱包ID", type="string"),
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

        Wallet::click($id);

        $key = "wallets-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $wallet = Wallet::findOrFail($id);
            $site = $wallet->site;
            $theme = $wallet->site->mobile_theme->name;
            $wallet->content = replace_content_url($wallet->content);
            return view("themes.$theme.wallets.detail", compact('site', 'wallet'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/wallets/share",
     *   summary="获取钱包分享页",
     *   tags={"/wallets 钱包"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="钱包ID", type="string"),
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

        Wallet::click($id);

        $key = "wallets-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $wallet = Wallet::findOrFail($id);
            $site = $wallet->site;
            $theme = $wallet->site->mobile_theme->name;
            $wallet->content = replace_content_url($wallet->content);
            $share = 1;
            return view("themes.$theme.wallets.detail", compact('site', 'wallet', 'share'))->__toString();
        });
    }
}