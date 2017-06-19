<?php

namespace App\Api\Controllers;

use App\Models\Content;
use App\Models\ContentItem;
use App\Models\Favorite;
use DB;
use Exception;
use Request;


class FavoriteController extends BaseController
{
    /**
     * @SWG\Get(
     *   path="/favorites/list",
     *   summary="获取收藏列表",
     *   tags={"/favorites 收藏"},
     *   @SWG\Parameter(name="site_id", in="query", required=true, description="站点ID", type="string"),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
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
        $page_size = Request::get('page_size');
        $page = Request::get('page');

        try {
            $member = \JWTAuth::parseToken()->authenticate();
            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        $favorites = Favorite::with('content.items')
            ->where('site_id', $site_id)
            ->where('member_id', $member->id)
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $favorites = $favorites->filter(function ($favorite) {
            return !empty($favorite->content);
        });

        $favorites->transform(function ($favorite) {
            return [
                'favorite_id' => $favorite->id,
                'id' => $favorite->content->id,
                'type' => $favorite->content->type,
                'title' => $favorite->content->title,
                'subtitle' => $favorite->content->subtitle,
                'keywords' => $favorite->content->keywords,
                'author' => $favorite->content->author,
                'tags' => $favorite->content->tags,
                'source' => $favorite->content->source,
                'link_type' =>$favorite->content->link_type,
                'link' => $favorite->content->link,
                'image_url' => get_image_url($favorite->content->image_url),
                'video_url' => get_video_url($favorite->content->video_url),
                'live_url' => $favorite->content->live_url,
                'video_duration' => $favorite->content->video_duration,
                'images' => $favorite->content->items()->orderBy('sort')->get()->where('type', ContentItem::TYPE_IMAGE)->transform(function ($item) use ($favorite) {
                    return [
                        'id' => $item->id,
                        'title' => !empty($item->title) ?: $favorite->content->title,
                        'url' => get_image_url($item->url),
                        'description' => $item->description,
                    ];
                }),
                'summary' => $favorite->content->summary,
                'clicks' => $favorite->content->clicks + $favorite->content->views,
                'comments' => $favorite->content->comments,
                'favorites' => $favorite->content->favorites,
                'is_top' => $favorite->content->is_top,
                'time' => $favorite->content->published_at->format('m-d H:i'),
                'time_trans' => time_trans(strtotime($favorite->content->published_at)),
            ];
        });

        return $this->responseSuccess(array_values($favorites->toArray()));
    }

    /**
     * @SWG\Get(
     *   path="/favorites/create",
     *   summary="添加收藏",
     *   tags={"/favorites 收藏"},
     *   @SWG\Parameter(name="content_id", in="query", required=true, description="内容ID", type="string"),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="收藏成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   ),
     *   @SWG\Response(
     *     response="405",
     *     description="收藏数量过多"
     *   )
     * )
     */
    public function create()
    {
        $content_id = Request::get('content_id');

        try {
            $member = \JWTAuth::parseToken()->authenticate();
            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        //检查收藏记录数是否过多
        $count = DB::table('favorites')->where('member_id', $member->id)
            ->count();

        if ($count >= 1000) {
            return $this->responseError('收藏数量过多');
        }

        //检查总记录数是否过多
        $count = DB::table('favorites')->count();
        if ($count >= 1000 * 1000) {
            return $this->responseError('收藏数量过多');
        }

        //判断是否存在
        $content = Content::find($content_id);

        if (empty($content)) {
            return $this->responseError('此内容ID不存在');
        }

        //判断此收藏是否已存在
        $favorite = Favorite::where('member_id', $member->id)
            ->where('content_id', $content_id)
            ->first();

        if (empty($favorite)) {
            //增加收藏数
            $content->favorites += 1;
            $content->save();

            //增加收藏记录
            $favorite = new Favorite();
            $favorite->site_id = $content->site_id;
            $favorite->category_id = $content->category_id;
            $favorite->content_id = $content_id;
            $favorite->title = $content->title;
            $favorite->member_id = $member->id;
            $favorite->save();
        }

        return $this->responseSuccess();
    }

    /**
     * @SWG\Get(
     *   path="/favorites/destroy",
     *   summary="取消收藏",
     *   tags={"/favorites 收藏"},
     *   @SWG\Parameter(name="favorite_ids", in="query", required=true, description="收藏ID", type="array", items={"type": "integer"}),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="收藏成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   )
     * )
     */
    public function destroy()
    {
        try {
            $member = \JWTAuth::parseToken()->authenticate();
            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        $favorite_ids = Request::get('favorite_ids');

        DB::table('favorites')->where('member_id', $member->id)
            ->whereIn('id', explode(',', $favorite_ids))->delete();

        return $this->responseSuccess();
    }

    /**
     * @SWG\Get(
     *   path="/favorites/delete",
     *   summary="删除收藏",
     *   tags={"/favorites 收藏"},
     *   @SWG\Parameter(name="content_id", in="query", required=true, description="内容ID", type="integer"),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="删除成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   )
     * )
     */
    public function delete()
    {
        try {
            $member = \JWTAuth::parseToken()->authenticate();
            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        $content_id = Request::get('content_id');

        Favorite::where('content_id', $content_id)->delete();

        return $this->responseSuccess();
    }

    /**
     * @SWG\Get(
     *   path="/favorites/exist",
     *   summary="内容是否收藏",
     *   tags={"/favorites 收藏"},
     *   @SWG\Parameter(name="content_id", in="query", required=true, description="内容ID", type="string"),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="已收藏"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="未收藏"
     *   )
     * )
     */
    public function exist()
    {
        try {
            $member = \JWTAuth::parseToken()->authenticate();
            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        $content_id = Request::get('content_id');

        $favorite = Favorite::where('member_id', $member->id)
            ->where('content_id', $content_id)
            ->first();

        if ($favorite) {
            return $this->responseSuccess();
        }
        else{
            return $this->responseError('未收藏');
        }
    }
}