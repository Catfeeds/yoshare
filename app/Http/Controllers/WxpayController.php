<?php
namespace App\Http\Controllers;

use App\Libraries\wePay\lib\WxPayApi;
use App\Libraries\wePay\lib\JsApiPay;
use App\Libraries\wePay\lib\WxPayUnifiedOrder;
use App\Libraries\wePay\lib\PayNotifyCallBack;
use App\Models\Domain;
use App\Models\Order;
use App\Models\Member;
use App\Models\Payment;
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
        $input->SetOut_trade_no($result['order_num'].date("YmdHis"));
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

    public function vipPay(Domain $domain, $type)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        //下单准备
        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();

        $price = Member::LEVEL[$type];
        //生成押金流水号
        $order = new OrderController();
        $vip_pay_num = $order->buildOrderNum();

        $payments = Payment::where('state', Payment::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        // 统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("yoshare_vip_pay");
        $input->SetOut_trade_no($vip_pay_num.date("YmdHis"));
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

        $system['title'] = '支付页';
        $system['back'] = '/member/vip';
        $system['mark'] = 'member';

        return view('themes.' . $domain->theme->name . '.members.pay', ['system' => $system, 'payments' => $payments, 'data' => $data]);
    }

    public function notify(){
        $notify = new PayNotifyCallBack();
        $notify->Handle(false);

        //商户处理回调结果
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $res = $this->xmlToArray($xml);

        if ($res->data["return_code"] == "SUCCESS") {
            $order = Order::where('order_num', $res->data["out_trade_no"])->first();
            $input['total_pay'] = $res->data["total_fee"];
            $input['paid_at'] = Carbon::now();
            $input['state'] = Order::STATE_PAID;
            $order->update($input);
        }

    }


    public function xmlToArray($xml)
    {
        libxml_disable_entity_loader(true);

        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        $val = json_decode(json_encode($xmlstring),true);

        return $val;
    }
}
