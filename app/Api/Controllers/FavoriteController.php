<?php

namespace App\Api\Controllers;

use App\Models\Module;
use App\Models\Favorite;
use App\Models\File;
use DB;
use Exception;
use Request;


class FavoriteController extends BaseController
{
    public function transform($favorite)
    {
        $attributes['favorite_id'] = $favorite->id;
        $attributes = $favorite->refer->getAttributes();
        $attributes['images'] = $favorite->refer->images()->transform(function ($item) use ($favorite) {
            return [
                'id' => $item->id,
                'title' => !empty($item->title) ?: $favorite->refer->title,
                'url' => get_image_url($item->url),
                'summary' => $item->summary,
            ];
        });
        $attributes['time'] = $favorite->refer->published_at->format('m-d H:i');
        $attributes['time_trans'] = time_trans(strtotime($favorite->refer->published_at));
        return $attributes;
    }

    /**
     * @SWG\Get(
     *   path="/favorites/list",
     *   summary="获取收藏列表",
     *   tags={"/favorites 收藏"},
     *   @SWG\Parameter(name="site_id", in="query", required=true, description="站点ID", type="string"),
     *   @SWG\Parameter(name="page_size", in="query", required=true, description="分页大小", type="integer"),
     *   @SWG\Parameter(name="page", in="query", required=true, description="分页序号", type="integer"),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
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

        $favorites = Favorite::where('site_id', $site_id)
            ->where('member_id', $member->id)
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $favorites = $favorites->filter(function ($favorite) {
            return $favorite->refer()->exists();
        });

        $favorites->transform(function ($favorite) {
            return $this->transform($favorite);
        });

        return $this->responseSuccess(array_values($favorites->toArray()));
    }

    /**
     * @SWG\Get(
     *   path="/favorites/create",
     *   summary="添加收藏",
     *   tags={"/favorites 收藏"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="ID", type="string"),
     *   @SWG\Parameter(name="type", in="query", required=true, description="类型", type="integer"),
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
        $id = Request::get('id');
        $type = Request::get('type');

        try {
            $member = \JWTAuth::parseToken()->authenticate();
            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        $module = Module::find($type);

        //检查收藏记录数是否过多
        $count = DB::table('favorites')
            ->where('refer_id', $id)
            ->where('refer_type', $module->model_class)
            ->where('member_id', $member->id)
            ->count();

        if ($count >= 1000) {
            return $this->responseError('收藏数量过多');
        }

        //检查总记录数是否过多
        $count = DB::table('favorites')
            ->where('refer_id', $id)
            ->where('refer_type', $module->model_class)
            ->count();
        if ($count >= 1000 * 1000) {
            return $this->responseError('收藏数量过多');
        }

        $__singular__ = call_user_func([$module->model_class, 'find'], $id);

        if (empty($__singular__)) {
            return $this->responseError('此ID不存在');
        }

        //判断此收藏是否已存在
        if (!Favorite::where('member_id', $member->id)->where('refer_id', $id)->where('refer_type', $module->model_class)->exists()) {
            //增加收藏数
            $__singular__->favorites += 1;
            $__singular__->save();

            //增加收藏记录
            $favorite = new Favorite();
            $favorite->site_id = $__singular__->site_id;
            $favorite->refer_id = $__singular__->id;
            $favorite->refer_type = $__singular__->getMorphClass();
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
     *   @SWG\Parameter(name="id", in="query", required=true, description="ID", type="integer"),
     *   @SWG\Parameter(name="type", in="query", required=true, description="类型", type="integer"),
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
        $id = Request::get('id');
        $type = Request::get('type');

        try {
            $member = \JWTAuth::parseToken()->authenticate();
            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        $module = Module::find($type);

        $favorite = Favorite::where('refer_id', $id)
            ->where('refer_type', $module->model_class)
            ->where('member_id', $member->id)
            ->first();

        if($favorite){
            $favorite->delete();
        }

        return $this->responseSuccess();
    }

    /**
     * @SWG\Get(
     *   path="/favorites/exist",
     *   summary="是否收藏",
     *   tags={"/favorites 收藏"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="ID", type="string"),
     *   @SWG\Parameter(name="type", in="query", required=true, description="类型", type="integer"),
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
        $id = Request::get('id');
        $type = Request::get('type');

        try {
            $member = \JWTAuth::parseToken()->authenticate();
            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        $module = Module::find($type);

        $favorite = Favorite::where('refer_id', $id)
            ->where('refer_type', $module->model_class)
            ->where('member_id', $member->id)
            ->first();

        if ($favorite) {
            return $this->responseSuccess();
        } else {
            return $this->responseError('未收藏');
        }
    }
}