<?php
namespace App\Http\Controllers;

use App\Libraries\wePay\lib\WxPayApi;
use App\Libraries\wePay\lib\JsApiPay;
use App\Libraries\wePay\lib\WxPayUnifiedOrder;
use App\Libraries\wePay\lib\PayNotifyCallBack;
use App\Libraries\wePay\lib\WxPayRefund;
use App\Libraries\wePay\lib\WxPayConfig;
use App\Models\Domain;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Member;
use App\Models\Bill;
use App\Models\Wallet;
use Carbon\Carbon;

ini_set('date.timezone','Asia/Shanghai');

class WxpayController extends Controller{

    public function orderPay(Domain $domain, $id)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();

        $result = Order::find($id);
        $result['price'] = $result['total_price']+$result['ship_price'];
        $payments = Payment::where('state', Payment::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        // 统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("yoshare_order");
        $input->SetAttach("yoshare_order");
        $input->SetOut_trade_no($result["order_num"]);
        $input->SetTotal_fee($result['price']*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("order");
        $input->SetNotify_url('http://'.$_SERVER['HTTP_HOST'].'/wxpay/notify');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);

        $editAddress = $tools->GetEditAddressParameters();

        $data['jsApiParameters'] = $jsApiParameters;
        $data['editAddress'] = $editAddress;

        $system['title'] = '支付页';
        $system['back'] = '/order/lists';
        $system['mark'] = 'member';

        return view('themes.' . $domain->theme->name . '.orders.pay', ['system' => $system, 'result' => $result, 'payments' => $payments, 'data' => $data]);
    }

    public function unblocked(Domain $domain, $id)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();

        $result = Order::find($id);
        $orderNum = $result['order_num'] . '1';
        $tenancy = ceil(((strtotime($result->paid_at) + \App\Models\Order::TENANCY) - time()) / 86400);
        //处理逾期业务
        if ($tenancy < 0) {
            $fine = $result->total_price * (ceil(abs($tenancy) / 30));
        }

        $payments = Payment::where('state', Payment::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        // 统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("yoshare_unblocked");
        $input->SetAttach("yoshare_unblocked");
        $input->SetOut_trade_no($orderNum);
        $input->SetTotal_fee($fine * 100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("order");
        $input->SetNotify_url('http://' . $_SERVER['HTTP_HOST'] . '/wxpay/notify');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);

        $editAddress = $tools->GetEditAddressParameters();

        $data['jsApiParameters'] = $jsApiParameters;
        $data['editAddress'] = $editAddress;

        $system['title'] = '支付页';
        $system['back'] = '/order/lists';
        $system['mark'] = 'member';

        return view('themes.' . $domain->theme->name . '.orders.pay', ['system' => $system, 'result' => $result, 'payments' => $payments, 'data' => $data]);
    }

    public function recharge(Domain $domain, $price)
    {
        if (empty($domain->site)) {
            return abort(501);
        }
        //todo
        if($price > 100){
            $type = 'deposit';
        }else{
            $type = 'balance';
        }

        //生成账单流水号
        $bill = new BillController();
        $billNum = $bill->buildBillNum();

        $payments = Payment::where('state', Payment::STATE_PUBLISHED)
            ->whereNotNull('payurl')
            ->orderBy('sort', 'desc')
            ->get();

        //下单准备
        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();

        // 统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("yoshare_".$type);
        $input->SetAttach("yoshare_".$type);
        $input->SetOut_trade_no($billNum);
        $input->SetTotal_fee($price*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag($type);
        $input->SetNotify_url('http://'.$_SERVER['HTTP_HOST'].'/wxpay/notify');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);

        $order = WxPayApi::unifiedOrder($input);

        $jsApiParameters = $tools->GetJsApiParameters($order);
        $editAddress = $tools->GetEditAddressParameters();

        $data['jsApiParameters'] = $jsApiParameters;
        $data['editAddress'] = $editAddress;
        $data['price'] = $price;

        $system['title'] = '支付页';
        $system['back'] = '/wallets/'.$type;
        $system['mark'] = 'member';
        $system['type'] = $type;

        return view('themes.' . $domain->theme->name . '.wallets.pay', ['system' => $system, 'payments' => $payments, 'data' => $data]);
    }

    public function notify()
    {
        $notify = new PayNotifyCallBack();
        $notify->Handle(false);
    }

    public function handle($data)
    {
        $member = Member::where('open_id', $data['openid'])->first();
        $wallet = Wallet::where('member_id', $member['id'])->first();

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
            $input['state'] = Wallet::STATE_NORMAL;
            //更新钱包押金
            $wallet->update($input);
            //更新会员等级
            $member->state = Member::STATE_ENABLED;
            $member->type = Member::TYPE_GOLD;
            $member->save();

        }elseif($data["return_code"] == "SUCCESS" && $data['attach'] == 'yoshare_balance'){
            $data['balance'] = $data["total_fee"]/100+array_search($data["total_fee"]/100, Wallet::VALUE['balance']);
            $wallet->update($data);
        } elseif ($data["return_code"] == "SUCCESS" && $data['attach'] == 'yoshare_unblocked') {
            //增加订单解冻时间
            $order = Order::where('order_num', $data["out_trade_no"])->first();
            $input['unblocked_at'] = time();
            $order->update($input);
        }

        //更新流水表
        $bill = [
            'member_id' => $member['id'],
            'bill_num' => $data["out_trade_no"],
            'type' => Bill::TYPES[$data['attach']],
            'money' => $data["total_fee"]/100,
            'state' => Bill::STATE_NORMAL,
        ];
        Bill::stores($bill);
        //更新用户积分
        $wallet->increment('points', $data["total_fee"]/100);

        //支付成功后的跳转
        $back_url = $_COOKIE['BackUrl'];
        if($data['attach'] == 'yoshare_deposit') {
            if(!empty($back_url)){
                return redirect($back_url);
            }else{
                return redirect('/wallets/show/deposit');
            }
        }else if($data['attach'] == 'yoshare_balance'){
            return redirect('/wallets/show/balance');
        }else{
            return redirect('/order/lists/nosend');
        }
    }

    public function refund($data)
    {
        if(isset($data["out_trade_no"]) && $data["out_trade_no"] != ""){
            $out_trade_no = $data["out_trade_no"];
            $total_fee = $data["total_fee"];
            $refund_fee = $data["refund_fee"];
            $out_refund_no = $data["out_refund_no"];

            $input = new WxPayRefund();
            $input->SetOut_trade_no($out_trade_no);
            $input->SetTotal_fee($total_fee);
            $input->SetRefund_fee($refund_fee);
            $input->SetOut_refund_no($out_refund_no);
            $input->SetOp_user_id(WxPayConfig::MCHID);

            return WxPayApi::refund($input);
        }
    }

}
