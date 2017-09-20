<?php

namespace App\Api\Controllers;

use App\Models\Feature;
use App\Models\Item;
use Request;

class FeatureController extends BaseController
{
    public function __construct()
    {
    }

    public function transform($feature)
    {
        $attributes = $feature->getAttributes();
        $attributes['images'] = $feature->images()->transform(function ($item) use ($feature) {
            return [
                'id' => $item->id,
                'title' => !empty($item->title) ?: $feature->title,
                'url' => get_image_url($item->url),
                'summary' => $item->summary,
            ];
        });
        $attributes['comment_count'] = $feature->comment_count;
        $attributes['favorite_count'] = $feature->favorite_count;
        $attributes['follow_count'] = $feature->follow_count;
        $attributes['like_count'] = $feature->like_count;
        $attributes['click_count'] = $feature->click_count;
        $attributes['created_at'] = empty($feature->created_at) ? '' : $feature->created_at->toDateTimeString();
        $attributes['updated_at'] = empty($feature->updated_at) ? '' : $feature->updated_at->toDateTimeString();
        return $attributes;
    }

    /**
     * @SWG\Get(
     *   path="/features",
     *   summary="获取专题列表",
     *   tags={"/features 专题"},
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

        $key = "feature-list-$site_id-$category_id-$page_size-$page";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page, $category_id) {
            $features = Feature::with('items')
                ->where('site_id', $site_id)
                ->where('category_id', $category_id)
                ->where('state', Feature::STATE_PUBLISHED)
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $features->transform(function ($feature) {
                return $this->transform($feature);
            });

            return $this->responseSuccess($features);
        });
    }

    /**
     * @SWG\Get(
     *   path="/features/search",
     *   summary="搜索专题",
     *   tags={"/features 专题"},
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

        $features = Feature::with('items')
            ->where('site_id', $site_id)
            ->where('title', 'like', '%' . $title . '%')
            ->where('state', Feature::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $features->transform(function ($feature) {
            return $this->transform($feature);
        });

        return $this->responseSuccess($features);
    }

    /**
     * @SWG\Get(
     *   path="/features/info",
     *   summary="获取专题信息",
     *   tags={"/features 专题"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="专题ID", type="string"),
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

        $key = "Feature-info-$id";

        return cache_remember($key, 1, function () use ($id) {
            $feature = Feature::find($id);
            if (empty($feature)) {
                return $this->responseFail('此ID不存在');
            }

            return $this->responseSuccess($this->transform($feature));
        });
    }

    /**
     * @SWG\Get(
     *   path="/features/detail",
     *   summary="获取专题详情页",
     *   tags={"/features 专题"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="专题ID", type="string"),
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

        $key = "Feature-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $feature = Feature::findOrFail($id);
            $site = $feature->site;
            $theme = $feature->site->mobile_theme;
            $feature->content = replace_content_url($feature->content);
            return view("themes.$theme.features.detail", compact('site', 'feature'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/features/share",
     *   summary="获取专题分享页",
     *   tags={"/features 专题"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="专题ID", type="string"),
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

        $key = "Feature-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $feature = Feature::findOrFail($id);
            $site = $feature->site;
            $theme = $feature->site->mobile_theme;
            $feature->content = replace_content_url($feature->content);
            $share = 1;
            return view("themes.$theme.features.detail", compact('site', 'feature', 'share'))->__toString();
        });
    }
}