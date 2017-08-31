<?php

namespace App\Api\Controllers;

use App\Models\Content;
use App\Models\ContentItem;
use App\Models\File;
use App\Models\Live;
use Request;

class __controller__ extends BaseController
{
    public function __construct()
    {
    }

    /**
     * @SWG\Get(
     *   path="/__module_path__/list",
     *   summary="获取__module_title__列表",
     *   tags={"/__module_path__ __module_title__"},
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

        $key = "__module_name__-list-$site_id-$category_id-$page_size-$page";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page, $category_id) {
            $__plural__ = __model__::with('files')
                ->where('site_id', $site_id)
                ->where('category_id', $category_id)
                ->where('state', Content::STATE_PUBLISHED)
                ->orderBy('is_top', 'desc')
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $__plural__->transform(function ($__singular__) {
                $attributes = $__singular__->getAttributes();
                $attributes['images'] = $__singular__->files()->where('type', File::TYPE_IMAGE)->orderBy('sort')->get()->transform(function ($item) use ($__singular__) {
                    return [
                        'id' => $item->id,
                        'title' => !empty($item->title) ?: $__singular__->title,
                        'url' => get_image_url($item->url),
                        'summary' => $item->summary,
                    ];
                });
                $attributes['created_at'] = empty($__singular__->created_at) ? '' : $__singular__->created_at->toDateTimeString();
                $attributes['updated_at'] = empty($__singular__->updated_at) ? '' : $__singular__->updated_at->toDateTimeString();
            });

            return $this->responseSuccess($__plural__);
        });
    }
}