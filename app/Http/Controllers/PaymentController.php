<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Jobs\PublishPage;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Domain;
use App\Models\Module;
use App\Models\UserLog;
use Auth;
use Carbon\Carbon;
use Gate;
use Request;
use Response;

/**
 * 支付方式
 */
class PaymentController extends Controller
{
    protected $base_url = '/admin/payments';
    protected $view_path = 'admin.payments';
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('name', 'Payment')->first();
    }

    public function show(Domain $domain, $id)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $payment = Payment::find($id);
        if (empty($payment)) {
            return abort(404);
        }
        $payment->incrementClick();

        return view('themes.' . $domain->theme->name . '.payments.detail', ['site' => $domain->site, 'payment' => $payment]);
    }

    public function slug(Domain $domain, $slug)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $payment = Payment::where('slug', $slug)
            ->first();
        if (empty($payment)) {
            return abort(404);
        }
        $payment->incrementClick();

        return view('themes.' . $domain->theme->name . '.payments.detail', ['site' => $domain->site, 'payment' => $payment]);
    }

    public function lists(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $payments = Payment::where('state', Payment::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $domain->theme->name . '.payments.index', ['site' => $domain->site, 'module' => $this->module, 'payments' => $payments]);
    }

    public function index()
    {
        if (Gate::denies('@payment')) {
            return abort(403);
        }

        $module = Module::transform($this->module->id);

        return view($this->view_path . '.index', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@payment-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        return view('admin.contents.create', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@payment-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        $payment = call_user_func([$this->module->model_class, 'find'], $id);
        $payment->images = null;
        $payment->videos = null;
        $payment->audios = null;
        $payment->tags = $payment->tags()->pluck('name')->toArray();

        return view('admin.contents.edit', ['module' => $module, 'content' => $payment, 'base_url' => $this->base_url]);
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

        $payment = Payment::stores($input);

        event(new UserLogEvent(UserLog::ACTION_CREATE . '支付方式', $payment->id, $this->module->model_class));

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

        $payment = Payment::updates($id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '支付方式', $payment->id, $this->module->model_class));

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
        $payment = Payment::find($id);

        if (empty($payment)) {
            return;
        }

        $payment->update(Request::all());
    }

    public function sort()
    {
        return Payment::sort();
    }

    public function top($id)
    {
        $payment = Payment::find($id);
        $payment->top = !$payment->top;
        $payment->save();
    }

    public function tag($id)
    {
        $tag = request('tag');
        $payment = Payment::find($id);
        if ($payment->tags()->where('name', $tag)->exists()) {
            $payment->tags()->where('name', $tag)->delete();
        } else {
            $payment->tags()->create([
                'site_id' => $payment->site_id,
                'name' => $tag,
                'sort' => strtotime(Carbon::now()),
            ]);
        }
    }

    public function state()
    {
        $input = request()->all();
        Payment::state($input);

        $ids = $input['ids'];
        $stateName = Payment::getStateName($input['state']);

        //记录日志
        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '支付方式' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }

        //发布页面
        $site = auth()->user()->site;
        if ($input['state'] == Payment::STATE_PUBLISHED) {
            foreach ($ids as $id) {
                $this->dispatch(new PublishPage($site, $this->module, $id));
            }
        }
    }

    public function table()
    {
        return Payment::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id));
    }

}
