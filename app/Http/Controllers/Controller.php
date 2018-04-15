<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 返回自定义数据
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($data)
    {
        return Response::json($data);
    }

    /**
     * 成功并返回数据
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($data = [], $url = '')
    {
        return $this->response([
            'status_code' => 200,
            'message' => 'success',
            'data' => $data,
            'url' => $url,
        ]);
    }

    /**
     * 错误并返回错误信息和状态码
     *
     * @param $message
     * @param int $status_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError($message, $status_code = 404)
    {
        \Log::debug('Error IP: ' . get_client_ip() . ', '. $message);
        return $this->response([
            'status_code' => $status_code,
            'message' => $message,
        ]);
    }
}
