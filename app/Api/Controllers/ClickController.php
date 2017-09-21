<?php

namespace App\Api\Controllers;

use App\Models\Module;
use Cache;
use Exception;
use Request;

class ClickController extends BaseController
{
    /**
     * @SWG\Post(
     *   path="/clicks/create",
     *   summary="添加点击数",
     *   tags={"/clicks 点击数"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="ID", type="string"),
     *   @SWG\Parameter(name="type", in="query", required=true, description="类型", type="string"),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
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

        $module = Module::findByName($type);
        if (!$module) {
            return $this->responseError('此类型不存在');
        }

        $model = call_user_func([$module->model_class, 'find'], $id);
        if (empty($model)) {
            return $this->responseError('此ID不存在');
        }

        $click = $model->clicks()->first();

        if (!$click) {
            $model->clicks()->create([
                'site_id' => $model->site_id,
                'count' => 1,
            ]);
        } else {
            $click->increment('count');

            Cache::forget($model->getTable() . "-click-$model->id");
        }

        return $this->responseSuccess();
    }

}