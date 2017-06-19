<?php

namespace App\Api\Controllers;

use App\Models\Category;
use App\Models\Content;
use App\Models\ContentItem;
use App\Models\Live;
use DB;
use Request;

class ContentController extends BaseController
{
    public function __construct()
    {
    }

    /**
     * @SWG\Get(
     *   path="/contents/list",
     *   summary="获取内容列表",
     *   tags={"/contents 内容"},
     *   @SWG\Parameter(name="category_id", in="query", required=true, description="栏目ID", type="string"),
     *   @SWG\Parameter(name="page_size", in="query", required=true, description="分页大小", type="integer"),
     *   @SWG\Parameter(name="page", in="query", required=true, description="分页序号", type="integer"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到路由"
     *   )
     * )
     */
    public function lists()
    {
        $site_id = Request::get('site_id') ? Request::get('site_id') : 1;
        $page_size = Request::get('page_size') ? Request::get('page_size') : 20;
        $page = Request::get('page') ? Request::get('page') : 1;
        $category_id = Request::get('category_id');

        $key = "content-list-$site_id-$page_size-$page-$category_id";

        return cache_remember($key, 1, function () use ($site_id, $page_size, $page, $category_id) {
            $contents = Content::with('items')
                ->where('site_id', $site_id)
                ->where('category_id', $category_id)
                ->where('state', Content::STATE_PUBLISHED)
                ->orderBy('is_top', 'desc')
                ->orderBy('sort', 'desc')
                ->skip(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();

            $contents->transform(function ($content) {
                return [
                    'id' => $content->id,
                    'type' => $content->type,
                    'title' => $content->title,
                    'subtitle' => $content->subtitle,
                    'keywords' => $content->keywords,
                    'author' => $content->author,
                    'tags' => $content->tags,
                    'source' => $content->source,
                    'link_type' =>$content->link_type,
                    'link' => $content->link,
                    'image_url' => get_image_url($content->image_url),
                    'video_url' => get_video_url($content->video_url),
                    'live_url' => $content->live_url,
                    'video_duration' => $content->video_duration,
                    'images' => $content->items()->orderBy('sort')->get()->where('type', ContentItem::TYPE_IMAGE)->transform(function ($item) use ($content) {
                        return [
                            'id' => $item->id,
                            'title' => !empty($item->title) ?: $content->title,
                            'url' => get_image_url($item->url),
                            'description' => $item->description,
                        ];
                    }),
                    'summary' => $content->summary,
                    'clicks' => $content->clicks + $content->views,
                    'comments' => $content->comments,
                    'favorites' => $content->favorites,
                    'is_top' => $content->is_top,
                    'time' => $content->published_at->format('m-d H:i'),
                    'time_trans' => time_trans(strtotime($content->published_at)),
                ];
            });

            return $this->responseSuccess($contents);
        });
    }

    /**
     * @SWG\Get(
     *   path="/contents/search",
     *   summary="搜索内容",
     *   tags={"/contents 内容"},
     *   @SWG\Parameter(name="title", in="query", required=true, description="搜索标题", type="string"),
     *   @SWG\Parameter(name="page_size", in="query", required=true, description="分页大小", type="integer"),
     *   @SWG\Parameter(name="page", in="query", required=true, description="分页序号", type="integer"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到路由"
     *   )
     * )
     */
    public function search()
    {
        $site_id = Request::get('site_id') ? Request::get('site_id') : 1;
        $page_size = Request::get('page_size') ? Request::get('page_size') : 20;
        $page = Request::get('page') ? Request::get('page') : 1;
        $title = Request::get('title');

        $contents = Content::with('items', 'category')
            ->where('title', 'like', '%' . $title . '%')
            ->where('state', Content::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $contents->transform(function ($content) {
            return [
                'id' => $content->id,
                'type' => $content->type,
                'title' => $content->title,
                'subtitle' => $content->subtitle,
                'keywords' => $content->keywords,
                'author' => $content->author,
                'tags' => $content->tags,
                'source' => $content->source,
                'link_type' =>$content->link_type,
                'link' => $content->link,
                'image_url' => get_image_url($content->image_url),
                'video_url' => get_video_url($content->video_url),
                'live_url' => $content->live_url,
                'video_duration' => $content->video_duration,
                'images' => $content->items()->orderBy('sort')->get()->where('type', ContentItem::TYPE_IMAGE)->transform(function ($item) use ($content) {
                    return [
                        'id' => $item->id,
                        'title' => !empty($item->title) ?: $content->title,
                        'url' => get_image_url($item->url),
                        'description' => $item->description,
                    ];
                }),
                'summary' => $content->summary,
                'clicks' => $content->clicks + $content->views,
                'comments' => $content->comments,
                'favorites' => $content->favorites,
                'is_top' => $content->is_top,
                'time' => $content->published_at->format('m-d H:i'),
                'time_trans' => time_trans(strtotime($content->published_at)),
            ];
        });
        return $this->responseSuccess($contents);
    }

    /**
     * @SWG\Get(
     *   path="/contents/info",
     *   summary="获取内容信息",
     *   tags={"/contents 内容"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="内容ID", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到路由"
     *   )
     * )
     */
    public function info()
    {
        $site_id = Request::get('site_id') ? Request::get('site_id') : 1;
        $id = Request::get('id');

        $key = "content-info-$site_id-$id";

        return cache_remember($key, 1, function () use ($id) {
            $content = Content::findOrFail($id);

            return $this->responseSuccess([
                'id' => $content->id,
                'type' => $content->type,
                'title' => $content->title,
                'subtitle' => $content->subtitle,
                'keywords' => $content->keywords,
                'author' => $content->author,
                'tags' => $content->tags,
                'source' => $content->source,
                'link_type' =>$content->link_type,
                'link' => $content->link,
                'image_url' => get_image_url($content->image_url),
                'video_url' => get_video_url($content->video_url),
                'live_url' => $content->live_url,
                'video_duration' => $content->video_duration,
                'images' => $content->items()->orderBy('sort')->get()->where('type', ContentItem::TYPE_IMAGE)->transform(function ($item) use ($content) {
                    return [
                        'id' => $item->id,
                        'title' => !empty($item->title) ?: $content->title,
                        'url' => get_image_url($item->url),
                        'description' => $item->description,
                    ];
                }),
                'summary' => $content->summary,
                'content' => $content->content,
                'clicks' => $content->clicks + $content->views,
                'comments' => $content->comments,
                'favorites' => $content->favorites,
                'is_top' => $content->is_top,
                'time' => $content->published_at->format('m-d H:i'),
                'time_trans' => time_trans(strtotime($content->published_at)),
            ]);
        });
    }

    /**
     * @SWG\Get(
     *   path="/contents/about",
     *   summary="获取相关内容",
     *   tags={"/contents 内容"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="内容ID", type="integer"),
     *   @SWG\Parameter(name="limit", in="query", required=true, description="限制数量", type="integer"),
     *   @SWG\Parameter(name="sort_type", in="query", required=true, description="排序类型(1:最新,2:点击数)", type="integer"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到路由"
     *   )
     * )
     */
    public function about()
    {
        $site_id = Request::get('site_id') ? Request::get('site_id') : 1;
        $id = Request::get('id');
        $limit = Request::get('limit');
        $sort_type = Request::get('sort_type');

        $key = "content-about-$site_id-$id";

        return cache_remember($key, 1, function () use ($id, $limit, $sort_type) {
            $content = Content::findOrFail($id);

            if (empty($content->keywords)) {
                return $this->responseSuccess([]);
            }

            $keywords = explode(' ', $content->keywords);
            $sort_field = 'sort';
            if ($sort_type == 2) {
                $sort_field = 'clicks';
            }

            $contents = Content::with('items', 'category')
                ->where('id', '<>', $id)
                ->where('category_id', $content->category_id)
                ->where(function ($query) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $query->orWhere('keywords', 'like', '%' . $keyword . '%');
                    }
                })
                ->where('state', Content::STATE_PUBLISHED)
                ->orderBy($sort_field, 'desc')
                ->limit($limit)
                ->get();

            $contents->transform(function ($content) {
                return [
                    'id' => $content->id,
                    'type' => $content->type,
                    'title' => $content->title,
                    'subtitle' => $content->subtitle,
                    'keywords' => $content->keywords,
                    'author' => $content->author,
                    'tags' => $content->tags,
                    'source' => $content->source,
                    'link_type' =>$content->link_type,
                    'link' => $content->link,
                    'image_url' => get_image_url($content->image_url),
                    'video_url' => get_video_url($content->video_url),
                    'live_url' => $content->live_url,
                    'video_duration' => $content->video_duration,
                    'images' => $content->items()->orderBy('sort')->get()->where('type', ContentItem::TYPE_IMAGE)->transform(function ($item) use ($content) {
                        return [
                            'id' => $item->id,
                            'title' => !empty($item->title) ?: $content->title,
                            'url' => get_image_url($item->url),
                            'description' => $item->description,
                        ];
                    }),
                    'summary' => $content->summary,
                    'clicks' => $content->clicks + $content->views,
                    'comments' => $content->comments,
                    'favorites' => $content->favorites,
                    'is_top' => $content->is_top,
                    'time' => $content->published_at->format('m-d H:i'),
                    'time_trans' => time_trans(strtotime($content->published_at)),
                ];
            });

            return $this->responseSuccess($contents);
        });
    }

    /**
     * @SWG\Get(
     *   path="/contents/detail",
     *   summary="获取内容详情页",
     *   tags={"/contents 内容"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="内容ID", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到路由"
     *   )
     * )
     */
    public function detail()
    {
        $site_id = Request::get('site_id') ? Request::get('site_id') : 1;
        $id = Request::get('id');
        $header = Request::has('header') ? Request::get('header') : 1;

        //增加点击次数
        Content::click($id);

        $key = "content-detail-$site_id-$id-$header";

        return cache_remember($key, 1, function () use ($id, $header) {
            $content = Content::findOrFail($id);
            $template = $content->category->template;
            $content->content = replace_content_url($content->content);
            return view("templates.contents.$template", compact('content', 'header'))->__toString();
        });
    }

    /**
     * @SWG\Get(
     *   path="/contents/share",
     *   summary="获取分享详情页",
     *   tags={"/contents 内容"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="内容ID", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到路由"
     *   )
     * )
     */
    public function share()
    {
        $site_id = Request::get('site_id') ? Request::get('site_id') : 1;
        $id = Request::get('id');

        //增加点击次数
        Content::click($id);

        $key = "content-share-$site_id-$id";
        return cache_remember($key, 1, function () use ($id) {
            $content = Content::findOrFail($id);
            $template = $content->category->template;
            $content->content = replace_content_url($content->content);
            return view("templates.contents.$template-share", compact('content'))->__toString();
        });
    }
}