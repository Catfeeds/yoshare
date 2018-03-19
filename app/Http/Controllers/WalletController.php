<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Jobs\PublishPage;
use App\Models\Wallet;
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

    public function show(Domain $domain, $id)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $wallet = Wallet::find($id);
        if (empty($wallet)) {
            return abort(404);
        }
        $wallet->incrementClick();

        return view('themes.' . $domain->theme->name . '.wallets.detail', ['site' => $domain->site, 'wallet' => $wallet]);
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

        $wallets = Wallet::where('state', Wallet::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
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
        $wallet->images = null;
        $wallet->videos = null;
        $wallet->audios = null;
        $wallet->tags = $wallet->tags()->pluck('name')->toArray();

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
        return redirect($this->base_url);
    }

    public function comments($refer_id)
    {
        $refer_type = $this->module->model_class;
        return view('admin.comments.list', compact('refer_id', 'refer_type'));
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

    public function state()
    {
        $input = request()->all();
        Wallet::state($input);

        $ids = $input['ids'];
        $stateName = Wallet::getStateName($input['state']);

        //记录日志
        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '钱包' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }

        //发布页面
        $site = auth()->user()->site;
        if ($input['state'] == Wallet::STATE_PUBLISHED) {
            foreach ($ids as $id) {
                $this->dispatch(new PublishPage($site, $this->module, $id));
            }
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
}
