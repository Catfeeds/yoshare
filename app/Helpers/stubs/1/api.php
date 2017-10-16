<?php

namespace Modules\__module_name__\Api;

use App\Api\Controllers\BaseController;
use Modules\__module_name__\Models\__model__;
use Request;

class __controller__ extends BaseController
{
    public function __construct()
    {
    }

    public function transform($__singular__)
    {
        $attributes = $__singular__->getAttributes();
        $attributes['images'] = $__singular__->images()->transform(function ($item) use ($__singular__) {
            return [
                'id' => $item->id,
                'title' => !empty($item->title) ?: $__singular__->title,
                'url' => get_image_url($item->url),
                'summary' => $item->summary,
            ];
        });
        $attributes['comment_count'] = $__singular__->comment_count;
        $attributes['favorite_count'] = $__singular__->favorite_count;
        $attributes['follow_count'] = $__singular__->follow_count;
        $attributes['like_count'] = $__singular__->like_count;
        $attributes['click_count'] = $__singular__->click_count;
        $attributes['created_at'] = empty($__singular__->created_at) ? '' : $__singular__->created_at->toDateTimeString();
        $attributes['updated_at'] = empty($__singular__->updated_at) ? '' : $__singular__->updated_at->toDateTimeString();
        return $attributes;
    }

    /**
     * @SWG\Get(
     *   path="/__module_path__",
     *   summary="获取__module_title__列表",
     *   tags={"/__module_path__ __module_title__"},
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

        $key = "__singular__-list-$site_id-$page_size-$page";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page) {
            $__plural__ = __model__::with('items')
                ->where('site_id', $site_id)
                ->where('state', __model__::STATE_PUBLISHED)
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $__plural__->transform(function ($__singular__) {
                return $this->transform($__singular__);
            });

            return $this->responseSuccess($__plural__);
        });
    }

    /**
     * @SWG\Get(
     *   path="/__module_path__/search",
     *   summary="搜索__module_title__",
     *   tags={"/__module_path__ __module_title__"},
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

        $__plural__ = __model__::with('items')
            ->where('site_id', $site_id)
            ->where('title', 'like', '%' . $title . '%')
            ->where('state', __model__::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $__plural__->transform(function ($__singular__) {
            return $this->transform($__singular__);
        });

        return $this->responseSuccess($__plural__);
    }

    /**
     * @SWG\Get(
     *   path="/__module_path__/info",
     *   summary="获取__module_title__信息",
     *   tags={"/__module_path__ __module_title__"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="__module_title__ID", type="string"),
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

        __model__::click($id);

        $key = "__plural__-info-$id";

        return cache_remember($key, 1, function () use ($id) {
            $__singular__ = __model__::find($id);
            if (empty($__singular__)) {
                return $this->responseFail('此ID不存在');
            }

            return $this->responseSuccess($this->transform($__singular__));
        });
    }

    /**
     * @SWG\Get(
     *   path="/__module_path__/detail",
     *   summary="获取__module_title__详情页",
     *   tags={"/__module_path__ __module_title__"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="__module_title__ID", type="string"),
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

        __model__::click($id);

        $key = "__plural__-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $__singular__ = __model__::where('id', $id)
                ->where('state', __model__::STATE_PUBLISHED)
                ->first();
            $site = $__singular__->site;
            $theme = $__singular__->site->mobile_theme->name;
            $__singular__->content = replace_content_url($__singular__->content);
            return view("$theme.__module_path__.detail", compact('site', '__singular__'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/__module_path__/share",
     *   summary="获取__module_title__分享页",
     *   tags={"/__module_path__ __module_title__"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="__module_title__ID", type="string"),
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

        __model__::click($id);

        $key = "__plural__-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $__singular__ = __model__::where('id', $id)
                ->where('state', __model__::STATE_PUBLISHED)
                ->first();
            $site = $__singular__->site;
            $theme = $__singular__->site->mobile_theme->name;
            $__singular__->content = replace_content_url($__singular__->content);
            $share = 1;
            return view("$theme.__module_path__.detail", compact('site', '__singular__', 'share'))->__toString();
        });
    }
}