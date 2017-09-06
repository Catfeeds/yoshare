<?php

namespace App\Api\Controllers;

use App\Models\__model__;
use App\Models\File;
use App\Models\Option;
use App\Models\Comment;
use Request;
use Exception;

class __controller__ extends BaseController
{
    public function __construct()
    {
    }

    public function transform($__singular__)
    {
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
        return $attributes;
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

        $__plural__ = __model__::with('files')
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

        $key = "__module_name__-info-$id";

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

        $key = "__module_name__-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $__singular__ = __model__::findOrFail($id);
            $site = $__singular__->site;
            $theme = $__singular__->site->mobile_theme;
            $__singular__->content = replace_content_url($__singular__->content);
            return view("themes.$theme.__module_path__.detail", compact('site', '__singular__'))->__toString();
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

        $key = "__module_name__-detail-$id";

        return cache_remember($key, 1, function () use ($id) {
            $__singular__ = __model__::findOrFail($id);
            $site = $__singular__->site;
            $theme = $__singular__->site->mobile_theme;
            $__singular__->content = replace_content_url($__singular__->content);
            $share = 1;
            return view("themes.$theme.__module_path__.detail", compact('site', '__singular__', 'share'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/__module_path__/comments/create",
     *   summary="发表__module_title__评论",
     *   tags={"/__module_path__ __module_title__"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="__module_title__ID", type="string"),
     *   @SWG\Parameter(name="content", in="query", required=true, description="评论__module_title__", type="string"),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="评论成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   )
     * )
     */
    public function create()
    {
        $id = Request::get('id');
        $commentContent = Request::get('content');

        try {
            $member = \JWTAuth::parseToken()->authenticate();
            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        //根据内容类型获取标题
        $__plural__ = __model__::find($id);

        //增加评论数
        $__plural__->comments += 1;
        $__plural__->save();

        //是否免审核
        $option = Option::getValue(Option::COMMENT_REQUIRE_PASS);

        //增加评论记录
        $comment = new Comment();
        $comment->site_id = $__plural__->site_id;
        $comment->refer_id = $__plural__->id;
        $comment->refer_type = $__plural__->getMorphClass();
        $comment->content = $commentContent;
        $comment->member_id = $member->id;
        $comment->ip = get_client_ip();
        $comment->state = $option ? Comment::STATE_NORMAL : Comment::STATE_PASSED;

        $comment->save();

        return $this->responseSuccess();
    }
}