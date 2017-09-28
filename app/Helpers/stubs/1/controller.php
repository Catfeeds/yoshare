<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Jobs\PublishPage;
use App\Models\__module_name__;
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
 * __module_title__
 */
class __controller__ extends Controller
{
    protected $base_url = '/admin/__module_path__';
    protected $view_path = 'admin.__module_path__';
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('name', '__module_name__')->first();
    }

    public function show(Domain $domain, $id)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $__singular__ = __module_name__::find($id);
        if (empty($__singular__)) {
            return abort(404);
        }

        return view('themes.' . $domain->theme->name . '.__module_path__.detail', ['site' => $domain->site, '__singular__' => $__singular__]);
    }

    public function slug(Domain $domain, $slug)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $__singular__ = __module_name__::where('slug', $slug)
            ->first();
        if (empty($__singular__)) {
            return abort(404);
        }

        return view('themes.' . $domain->theme->name . '.__module_path__.detail', ['site' => $domain->site, '__singular__' => $__singular__]);
    }

    public function lists(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $__plural__ = __module_name__::where('state', __module_name__::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $domain->theme->name . '.__module_path__.index', ['site' => $domain->site, 'module' => $this->module, '__plural__' => $__plural__]);
    }

    public function index()
    {
        if (Gate::denies('@__permission__')) {
            return abort(403);
        }

        $module = Module::transform($this->module->id);

        return view($this->view_path . '.index', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@__permission__-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        return view('admin.contents.create', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@__permission__-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        $__singular__ = call_user_func([$this->module->model_class, 'find'], $id);
        $__singular__->images = null;
        $__singular__->videos = null;
        $__singular__->audios = null;
        $__singular__->tags = $__singular__->tags()->pluck('name')->toArray();

        return view('admin.contents.edit', ['module' => $module, 'content' => $__singular__, 'base_url' => $this->base_url]);
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

        $__singular__ = __module_name__::stores($input);

        event(new UserLogEvent(UserLog::ACTION_CREATE . '__module_title__', $__singular__->id, $this->module->model_class));

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

        $__singular__ = __module_name__::updates($id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '__module_title__', $__singular__->id, $this->module->model_class));

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
        $__singular__ = __module_name__::find($id);

        if (empty($__singular__)) {
            return;
        }

        $__singular__->update(Request::all());
    }

    public function sort()
    {
        return __module_name__::sort();
    }

    public function top($id)
    {
        $__singular__ = __module_name__::find($id);
        $__singular__->top = !$__singular__->top;
        $__singular__->save();
    }

    public function tag($id)
    {
        $tag = request('tag');
        $__singular__ = __module_name__::find($id);
        if ($__singular__->tags()->where('name', $tag)->exists()) {
            $__singular__->tags()->where('name', $tag)->delete();
        } else {
            $__singular__->tags()->create([
                'site_id' => $__singular__->site_id,
                'name' => $tag,
                'sort' => strtotime(Carbon::now()),
            ]);
        }
    }

    public function state()
    {
        $input = request()->all();
        __module_name__::state($input);

        $ids = $input['ids'];
        $stateName = __module_name__::getStateName($input['state']);

        //记录日志
        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '__module_title__' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }

        //发布页面
        $site = auth()->user()->site;
        if ($input['state'] == __module_name__::STATE_PUBLISHED) {
            foreach ($ids as $id) {
                $this->dispatch(new PublishPage($site, $this->module, $id));
            }
        }
    }

    public function table()
    {
        return __module_name__::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id));
    }
}
