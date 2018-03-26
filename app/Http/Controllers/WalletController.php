<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Models\Bill;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\Domain;
use App\Models\Module;
use App\Models\UserLog;
use App\Models\Member;
use App\Models\Payment;
use Auth;
use Carbon\Carbon;
use Gate;
use Request;
use Response;

/**
 * 钱包
 */
class WalletController extends Controller
{
    protected $base_url = '/admin/wallets';
    protected $view_path = 'admin.wallets';
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('name', 'Wallet')->first();
    }

    public function show(Domain $domain, $type)
    {
        if (empty($domain->site)) {
            return abort(501);
        }
        $member = Member::getMember();
        $wallet = $member->wallet()->first();

        if (empty($wallet)) {
            return abort(404);
        }

        $system['title'] = Wallet::TYPE[$type];
        $system['back'] = '/member';
        $system['mark'] = 'member';
        $system['type'] = $type;
        $system['vip_level'] = $member['type'];

        return view('themes.' . $domain->theme->name . '.wallets.index', ['member' => $member, 'system' => $system, 'wallet' => $wallet]);
    }


    public function price(Domain $domain, $type)
    {
        if (empty($domain->site)) {
            return abort(501);
        }
        $level = Member::getMember()->type;

        if($type == 'balance' || $level == Member::TYPE_ORDINARY ){
            $chooses = Wallet::VALUE[$type];
        }else{
            $chooses = Wallet::VALUE_UP[$level];
        }

        $system['title'] = '充值页';
        $system['back'] = '/wallets/show/'.$type;
        $system['mark'] = 'member';
        $system['type'] = $type;

        return view('themes.' . $domain->theme->name . '.wallets.price', ['system' => $system, 'chooses' => $chooses]);
    }

    public function slug(Domain $domain, $slug)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $wallet = Wallet::where('slug', $slug)
            ->first();
        if (empty($wallet)) {
            return abort(404);
        }
        $wallet->incrementClick();

        return view('themes.' . $domain->theme->name . '.wallets.detail', ['site' => $domain->site, 'wallet' => $wallet]);
    }

    public function lists(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $wallets = Wallet::orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $domain->theme->name . '.wallets.index', ['site' => $domain->site, 'module' => $this->module, 'wallets' => $wallets]);
    }

    public function index()
    {
        if (Gate::denies('@wallet')) {
            return abort(403);
        }

        $module = Module::transform($this->module->id);

        return view($this->view_path . '.index', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@wallet-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        return view('admin.contents.create', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@wallet-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        $wallet = call_user_func([$this->module->model_class, 'find'], $id);

        return view('admin.contents.edit', ['module' => $module, 'content' => $wallet, 'base_url' => $this->base_url]);
    }

    public function store()
    {
        $input = Request::all();
        $input['site_id'] = Auth::user()->site_id;
        $input['user_id'] = Auth::user()->id;

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $wallet = Wallet::stores($input);

        event(new UserLogEvent(UserLog::ACTION_CREATE . '钱包', $wallet->id, $this->module->model_class));

        \Session::flash('flash_success', '添加成功');
        return redirect($this->base_url);
    }

    public function update($id)
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $wallet = Wallet::updates($id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '钱包', $wallet->id, $this->module->model_class));

        \Session::flash('flash_success', '修改成功!');

        return redirect('/admin/members');
    }

    public function refund($id)
    {
        //微信退款
        $wePay = new WxpayController();
        $wallet = Wallet::find($id);
        $member = Member::find($wallet['member_id']);

        //一次性充值押金
        $bill = Bill::where('member_id', $wallet['member_id'])
            ->where('type', Bill::TYPES['yoshare_deposit'])
            ->where('money', $wallet['deposit'])
            ->first();

        if($bill){
            //生成账单流水号,用以记录账单历史
            $billObj = new BillController();
            $billNum = $billObj->buildBillNum();

            //注意：total_fee和refund_fee均为分。
            $data['total_fee'] = $wallet['deposit']*100;
            $data['refund_fee'] = $bill['money']*100;
            $data['out_trade_no'] = $bill['bill_num'];
            $data['out_refund_no'] = $billNum;
            $res = $wePay->refund($data);

            if($res['return_code'] == 'SUCCESS'){
                //更新用户状态以及用户等级
                $data['state'] = Member::STATE_REFUNDED;
                $data['type'] = Member::TYPE_ORDINARY;
                $member->update($data);
                //更新用户钱包
                $input['deposit'] = $wallet['deposit']-$bill['money'];
                $input['state'] = Wallet::STATE_REFUNDED;
                $wallet->update($input);

                //添加账单流水
                Bill::stores([
                    'member_id' => $wallet['member_id'],
                    'bill_num' => $billNum,
                    'type' => Bill::TYPES['yoshare_refund'],
                    'money' => $bill['money'],
                ]);

                //软删掉之前的押金充值流水订单
                $bill->delete();

                return $this->responseSuccess($res);
            }
        }else{
            //多次性升级会员，查询账单中押金充值记录
            $money = Bill::where('member_id', $wallet['member_id'])
                ->where('type', Bill::TYPES['yoshare_deposit'])
                ->pluck('money')
                ->toArray();

            $bills = Bill::where('member_id', $wallet['member_id'])
                ->where('type', Bill::TYPES['yoshare_deposit'])
                ->get();

            foreach ($bills as $bill){

                //生成账单流水号,用以记录账单历史
                $billObj = new BillController();
                $billNum = $billObj->buildBillNum();

                //注意：total_fee和refund_fee均为分。
                $data['total_fee'] = $bill['money']*100;
                $data['refund_fee'] = $bill['money']*100;
                $data['out_trade_no'] = $bill['bill_num'];
                $data['out_refund_no'] = $billNum;
                $res = $wePay->refund($data);
                if($res['return_code'] == 'SUCCESS'){

                    //更新用户钱包
                    $input['deposit'] = $wallet['deposit']-$bill['money'];
                    $input['state'] = Wallet::STATE_REFUNDED;
                    $wallet->update($input);

                    Bill::stores([
                        'member_id' => $wallet['member_id'],
                        'bill_num' => $billNum,
                        'type' => Bill::TYPES['yoshare_refund'],
                        'money' => $bill['money'],
                    ]);
                    //软删掉之前的押金充值流水订单
                    $bill->delete();
                }
            }
            //更新用户状态以及用户等级
            $data['state'] = Member::STATE_REFUNDED;
            $data['type'] = Member::TYPE_ORDINARY;
            $member->update($data);

            return $this->responseSuccess();
        }

    }

    public function save($id)
    {
        $wallet = Wallet::find($id);

        if (empty($wallet)) {
            return;
        }

        $wallet->update(Request::all());
    }

    public function sort()
    {
        return Wallet::sort();
    }

    public function top($id)
    {
        $wallet = Wallet::find($id);
        $wallet->top = !$wallet->top;
        $wallet->save();
    }

    public function tag($id)
    {
        $tag = request('tag');
        $wallet = Wallet::find($id);
        if ($wallet->tags()->where('name', $tag)->exists()) {
            $wallet->tags()->where('name', $tag)->delete();
        } else {
            $wallet->tags()->create([
                'site_id' => $wallet->site_id,
                'name' => $tag,
                'sort' => strtotime(Carbon::now()),
            ]);
        }
    }

    public function state($id)
    {
        $input = request()->all();
        $wallet = Wallet::find($id);
        $res = $wallet->update($input);

        if($res){
            if($input['state'] == Wallet::STATE_REFUNDING){
                //更新用户状态
                $member = Member::find($wallet['member_id']);
                $data['state'] = Member::STATE_REFUNDING;
                $member->update($data);
            }

            return $this->responseSuccess($res);
        }else{
            return $this->responseError('申请失败，请稍候再试');
        }
    }

    public function table()
    {
        return Wallet::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id));
    }

    public function wallet($type)
    {
        try {
            $member = Member::getMember();

            if (!$member) {
                return $this->responseError('您还未登录,请登录后操作', 401);
            }
        } catch (Exception $e) {
            return $this->responseError('您还未登录,请登录后操作', 401);
        }


        $wallet = $member->wallet()->first();

        return $this->responseSuccess($wallet[$type]);
    }

    public function pay()
    {
        $input = Request::all();
        $type = $input['type'];
        $wallet = Member::getMember()->wallet()->first();
        $input[$type] = $wallet[$type]-$input['price'];
        $res = $wallet->update($input);
        if($res){
            //处理订单
            $order = Order::find($input['order_id']);
            $input['total_pay'] = $input['price'];
            $input['paid_at'] = Carbon::now();
            $input['pay_id'] = Payment::BalanceID;
            $input['state'] = Order::STATE_PAID;
            $order->update($input);

            return $this->responseSuccess($res);
        }
    }
}
