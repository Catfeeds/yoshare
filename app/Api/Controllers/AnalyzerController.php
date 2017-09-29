<?php

namespace App\Api\Controllers;

use App\Models\PvLog;
use App\Models\Site;
use Request;

class AnalyzerController extends BaseController
{
    /**
     * @SWG\Get(
     *   path="/analyzers/web",
     *   summary="记录页面访问",
     *   tags={"/analyzers 分析"},
     *   @SWG\Parameter(name="app_key", in="query", required=false, description="App Key", type="string"),
     *   @SWG\Parameter(name="url", in="query", required=true, description="URL", type="string"),
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
    public function web()
    {
        $app_key = Request::get('app_key');
        $url = Request::get('url');

        //验证app_key

        //获取站点ID
        $site_id = Site::ID_DEFAULT;
        $sites = cache_remember('site-all', 1, function () {
            return Site::all();
        });

        foreach ($sites as $site) {
            if (str_contains(strtolower($url), strtolower($site->domain))) {
                $site_id = $site->id;
                break;
            }
        }

        //记录PV日志
        PvLog::create([
            'site_id' => $site_id,
            'url' => $url,
            'ip' => get_client_ip()
        ]);

        //记录IP日志

        //记录UV日志

        //记录浏览器日志

        return $this->responseSuccess();
    }

}