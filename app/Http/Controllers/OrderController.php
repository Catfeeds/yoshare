<?php

namespace App\Http\Controllers;

use App\Libraries\wePay\lib\WxPayApi;
use App\Libraries\wePay\lib\JsApiPay;
use App\Libraries\wePay\lib\WxPayUnifiedOrder;
use App\Libraries\WePay\Example\CLogFileHandler;
use App\Libraries\WePay\Example\Log;
use App\Libraries\WePay\lib\WxPayConfig;
use App\Events\UserLogEvent;
use App\Jobs\PublishPage;
use App\Models\Dictionary;
use App\Models\Order;
use App\Models\Category;
use App\Models\Domain;
use App\Models\Module;
use App\Models\Payment;
use App\Models\UserLog;
use App\Models\Member;
use App\Models\Cart;
use App\Models\Goods;
use App\Models\Address;
use Carbon\Carbon;
use Gate;
use Request;
use Response;

/**
 * 订单
 */
class OrderController extends Controller
{
    protected $base_url = '/admin/orders';
    protected $view_path = 'admin.orders';
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('name', 'Order')->first();
    }

    public function show(Domain $domain, $id)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $order = Order::find($id);
        if (empty($order)) {
            return abort(404);
        }
        $order->incrementClick();

        return view('themes.' . $domain->theme->name . '.orders.detail', ['site' => $domain->site, 'order' => $order]);
    }

    public function slug(Domain $domain, $slug)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $order = Order::where('slug', $slug)
            ->first();
        if (empty($order)) {
            return abort(404);
        }
        $order->incrementClick();

        return view('themes.' . $domain->theme->name . '.orders.detail', ['site' => $domain->site, 'order' => $order]);
    }

    public function buildOrderNum()
    {
        //生成流水号并保存至$file文件中
        $file = "order.txt";
        if(!file_exists($file)){
            if($handle = fopen($file,"a+")){
                $textTime = date("mdY");
                $num_order_new = str_pad($textTime,9,'0',STR_PAD_RIGHT);
                fwrite($handle,$num_order_new);
                $content = $num_order_new;
                fclose($handle);
            }else{
                $msg = '流水号创建失败！!';// TODO:
                $this->responseError($msg, 404);
            }
        }else{
            if($handle = fopen($file,"r+")){
                $content = file_get_contents($file);
                $new = $content+1;
                if(!fwrite($handle,$new)){
                    $msg = 'ERROR!';
                    $this->responseError($msg, 500);
                }
                fclose($handle);
            }
        }
        return $content.rand(100, 999);

    }

    public function place(Domain $domain, $ids)
    {
        $total_price = 0;
        $carts = [];

        //分割购物车ID字符串为数组
        $cart_ids = explode(',', $ids);

        if (empty($domain->site)) {
            return abort(501);
        }

        if (empty(Member::checkLogin())) {
            return view('auth.login');
        }

        $system['mark'] = 'orders';
        $system['title'] = '提交订单';
        $member_id = Member::getMember()->id;

        //查询默认地址
        $address = Address::where('member_id', $member_id)
            ->where('is_default', Address::IS_DEFAULT)
            ->first();
        //拼接地址
        $province = Dictionary::find($address->province)->name;
        $city = Dictionary::find($address->city)->name;
        $town = Dictionary::find($address->town)->name;

        if($city !== $province){
            $address->detail = $province.'省'.$city.'市'.$town.$address->detail;
        }else{
            $address->detail = $city.'市'.$town.$address->detail;
        }

        $goods_ids = Cart::whereIn('id', $cart_ids)
            ->pluck('goods_id')
            ->toArray();

        $goodses = Goods::whereIn('id', $goods_ids)
            ->get();

        $numbers = Cart::whereIn('id', $cart_ids)
            ->pluck('number', 'goods_id')
            ->toArray();

        $prices = Cart::whereIn('id', $cart_ids)
            ->get()
            ->toArray();

        foreach ($prices as $k => $v){
            $total_price += $v['number'] * $v['price'];
        }

        //购物车总数量
        $number = !empty($numbers) ? array_sum($numbers) : 0;

        $carts['ids'] = $ids;
        $carts['number']  = $number;
        $carts['numbers'] = $numbers;
        $carts['total_price'] = $total_price;

        return view('themes.' . $domain->theme->name . '.orders.place', ['address' => $address, 'carts' => $carts, 'system' => $system, 'goodses' => $goodses]);

    }

    public function lists(Domain $domain, $state = '')
    {
        if (empty($domain->site)) {
            return abort(501);
        }
        if($state == 'nopay'){
            $filters['state'] = Order::STATE_NOPAY;
            $system['title'] = '待付款订单';
        }elseif($state == 'nosend'){
            $filters['state'] = Order::STATE_PAID;
            $system['title'] = '待发货订单';
        }elseif($state == 'sended'){
            $filters['state'] = Order::STATE_SENDED;
            $system['title'] = '待收货订单';
        }elseif($state == 'success'){
            $filters['state'] = Order::STATE_SUCCESS;
            $system['title'] = '已完成订单';
        }else{
            $filters['state'] = $state;
            $system['title'] = '全部订单';
        }

        $member_id = Member::getMember()->id;

        $orders = Order::where('member_id', $member_id)
            ->filter($filters)
            ->get();

        foreach($orders as $key => $order){
            $goods_ids = Cart::where('order_id', $order->id)
                ->pluck('goods_id')
                ->toArray();

            $orders[$key]['goodses'] = Goods::whereIn('id', $goods_ids)
                ->get();
        }

        $system['mark'] = Domain::MARK_MEMBER;

        return view('themes.' . $domain->theme->name . '.orders.index', ['site' => $domain->site, 'system' => $system, 'orders' => $orders, 'state' => $state]);
    }

    public function index()
    {
        if (Gate::denies('@order')) {
            return abort(403);
        }

        $module = Module::transform($this->module->id);

        return view($this->view_path . '.index', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@order-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        return view('admin.contents.create', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@order-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        $order = call_user_func([$this->module->model_class, 'find'], $id);
        $order->images = null;
        $order->videos = null;
        $order->audios = null;
        $order->tags = $order->tags()->pluck('name')->toArray();

        return view('admin.contents.edit', ['module' => $module, 'content' => $order, 'base_url' => $this->base_url]);
    }

    public function store()
    {
        $input = Request::all();

        $input['order_num'] = $this->buildOrderNum();
        $input['site_id'] = Member::getMember()->site_id;
        $input['member_id'] = Member::getMember()->id;

        $order = Order::stores($input);

        if($order){
            //修改购物车order_id
            $order_id = $order->id;
            $ids = array_filter(explode(',', $input['ids']));

            foreach($ids as $v){
                $cart = Cart::find($v);
                $data['order_id'] = $order_id;
                $result = $cart->update($data);
            }

            return $this->responseSuccess($order);

        }
        //event(new UserLogEvent(UserLog::ACTION_CREATE . '订单', $order->id, $this->module->model_class));

    }

    public function update($id)
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $order = Order::updates($id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '订单', $order->id, $this->module->model_class));

        \Session::flash('flash_success', '修改成功!');
        return redirect($this->base_url);
    }

    public function comments($refer_id)
    {
        $refer_type = $this->module->model_class;
        return view('admin.comments.list', compact('refer_id', 'refer_type'));
    }

    public function save($id)
    {
        $order = Order::find($id);

        if (empty($order)) {
            return;
        }

        $order->update(Request::all());
    }

    public function sort()
    {
        return Order::sort();
    }

    public function top($id)
    {
        $order = Order::find($id);
        $order->top = !$order->top;
        $order->save();
    }

    public function tag($id)
    {
        $tag = request('tag');
        $order = Order::find($id);
        if ($order->tags()->where('name', $tag)->exists()) {
            $order->tags()->where('name', $tag)->delete();
        } else {
            $order->tags()->create([
                'site_id' => $order->site_id,
                'name' => $tag,
                'sort' => strtotime(Carbon::now()),
            ]);
        }
    }

    public function state()
    {
        $input = request()->all();
        Order::state($input);

        $ids = $input['ids'];
        $stateName = Order::getStateName($input['state']);

        //记录日志
        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '订单' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }

        //发布页面
        $site = auth()->user()->site;
        if ($input['state'] == Order::STATE_PUBLISHED) {
            foreach ($ids as $id) {
                $this->dispatch(new PublishPage($site, $this->module, $id));
            }
        }
    }

    public function table()
    {
        return Order::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id));
    }

    public function destroy($id)
    {
        $order = Order::where('id', $id)->first();
        $result = $order->delete();

        if ($result) {
            return redirect('/order/lists');
        } else {
            //TODO
        }
    }

    public function pay(Domain $domain, $id)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $result = Order::find($id);
        $result['price'] = $result['total_price']+$result['ship_price'];
        $payments = Payment::where('state', Payment::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        dd(dirname('http://'.$_SERVER['HTTP_HOST']).'/wxpay/notify');

        // 统一下单
        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();


        $input = new WxPayUnifiedOrder();
        $input->SetBody("test");
        //$input->SetAttach("test");
        $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url(dirname('http://'.$_SERVER['HTTP_HOST']).'/wxpay/notify');
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

}
