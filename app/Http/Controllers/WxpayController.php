<?php
namespace App\Http\Controllers;

ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

use WePay\lib\CLogFileHandler;
use App\Libraries\wePay\lib\PayNotifyCallBack;
use WePay\Example\Log;

class WxpayController extends Controller{
    public function notify(){

        //初始化日志
        $logHandler= new CLogFileHandler("/storage/logs/wechat/".date('Y-m-d').'.log');
        $log = Log::Init($logHandler, 15);
        Log::DEBUG("begin notify");

        $notify = new PayNotifyCallBack();
        $notify->Handle(false);
        //商户处理回调结果
    }
}
