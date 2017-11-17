<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Jobs\PublishPage;
use App\Models\Order;
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

    public function lists(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $orders = Order::where('state', Order::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $domain->theme->name . '.orders.index', ['site' => $domain->site, 'module' => $this->module, 'orders' => $orders]);
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
        $input['site_id'] = Auth::user()->site_id;
        $input['user_id'] = Auth::user()->id;

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $order = Order::stores($input);

        event(new UserLogEvent(UserLog::ACTION_CREATE . '订单', $order->id, $this->module->model_class));

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
}
