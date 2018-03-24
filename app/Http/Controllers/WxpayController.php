<?php
namespace App\Http\Controllers;

use App\Libraries\wePay\lib\WxPayApi;
use App\Libraries\wePay\lib\JsApiPay;
use App\Libraries\wePay\lib\WxPayUnifiedOrder;
use App\Libraries\wePay\lib\PayNotifyCallBack;
use App\Models\Domain;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Wallet;
use Request;

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
        $input->SetGoods_tag("test");
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

    public function recharge(Domain $domain, $price)
    {
        if (empty($domain->site)) {
            return abort(501);
        }
        if($price > 100){
            $type = 'deposit';
        }else{
            $type = 'balance';
        }
        $payments = Payment::where('state', Payment::STATE_PUBLISHED)
            ->whereNotNull('payurl')
            ->orderBy('sort', 'desc')
            ->get();

        //下单准备
        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();

        //生成账单流水号
        $bill = new BillController();
        $billNum = $bill->buildBillNum();

        // 统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("yoshare_".$type);
        $input->SetAttach("yoshare_".$type);
        $input->SetOut_trade_no($billNum);
        $input->SetTotal_fee($price*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
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

    public function notify(){

        $notify = new PayNotifyCallBack();
        $notify->Handle(false);

    }

}
