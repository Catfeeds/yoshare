<?php
namespace App\Libraries\wePay\lib;

use App\Models\Member;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Bill;
use App\Models\Wallet;
use Carbon\Carbon;

ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

class PayNotifyCallBack extends WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        //Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result) && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        //Log::DEBUG("call back:" . json_encode($data));
        $notfiyOutput = array();

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }

        $member = Member::getMember();
        $wallet = Wallet::where('member_id', $member['id'])->first();

        //商户处理回调结果
        if ($data["return_code"] == "SUCCESS" && $data['attach'] == 'yoshare_order') {
            //修改订单信息
            $order = Order::where('order_num', $data["out_trade_no"])->first();
            $input['total_pay'] = $data["total_fee"]/100;
            $input['paid_at'] = Carbon::now();
            $input['pay_id'] = Payment::WeChatID;
            $input['state'] = Order::STATE_PAID;
            $order->update($input);
        }elseif($data["return_code"] == "SUCCESS" && $data['attach'] == 'yoshare_deposit'){
            $input['deposit'] = $data["total_fee"]/100;
            $wallet->update($input);
        }elseif($data["return_code"] == "SUCCESS" && $data['attach'] == 'yoshare_balance'){
            $input['balance'] = $data["total_fee"]/100+array_search($data["total_fee"]/100, Wallet::VALUE['balance']);
            $wallet->update($input);
        }

        //更新流水表
        $bill = [
            'member_id' => $member['id'],
            'bill_num' => $data["out_trade_no"],
            'type' => Bill::TYPES[$data['attach']],
            'money' => $data["total_fee"]/100,
        ];
        Bill::stores($bill);
        //更新用户积分
        $wallet->increment('points', $data["total_fee"]/100);

        return true;
    }
}