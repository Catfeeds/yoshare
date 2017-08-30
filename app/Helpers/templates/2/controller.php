<?php

namespace App\Http\Controllers;

use App\Models\__module_name__;
use App\Models\Category;
use App\Models\Content;
use App\Models\Module;
use App\Models\Site;
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
        $this->module = Module::transform(__module_name__::MODULE_ID);
    }

    public function show($id)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $__module_singular__ = __module_name__::find($id);
        if (empty($__module_singular__)) {
            return abort(404);
        }

        return view('themes.' . $site->theme . '.__module_path__.detail', ['site' => $site, '__module_singular__' => $__module_singular__]);
    }

    public function slug($slug)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $__module_singular__ = __module_name__::where('slug', $slug)
            ->first();
        if (empty($__module_singular__)) {
            return abort(404);
        }

        return view('themes.' . $site->theme . '.__module_path__.detail', ['site' => $site, '__module_singular__' => $__module_singular__]);
    }

    public function lists()
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $__module_plural__ = __module_name__::where('state', __module_name__::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $site->theme . '.__module_path__.index', ['site' => $site, 'module' => $this->module, '__module_plural__' => $__module_plural__]);
    }

    public function index()
    {
        if (Gate::denies('@__permission__')) {
            return abort(403);
        }

        return view($this->view_path . '.index', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@__permission__-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        return view('admin.contents.create', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@__permission__-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $__module_singular__ = call_user_func([$this->module->model_class, 'find'], $id);

        return view('admin.contents.edit', ['module' => $this->module, 'content' => $__module_singular__, 'base_url' => $this->base_url, 'back_url' => $this->base_url . '?category_id=' . $__module_singular__->category_id]);
    }

    public function store()
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $__module_singular__ = Content::stores($this->module, $input);

        \Session::flash('flash_success', '添加成功');
        return redirect($this->base_url . '?category_id=' . $__module_singular__->category_id);
    }

    public function update($id)
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $__module_singular__ = Content::updates($this->module, $id, $input);

        \Session::flash('flash_success', '修改成功!');
        return redirect($this->base_url . '?category_id=' . $__module_singular__->category_id);
    }

    public function save($id)
    {
        $__module_singular__ = __module_name__::find($id);

        if (empty($__module_singular__)) {
            return;
        }

        $__module_singular__->update(Request::all());
    }

    public function sort()
    {
        return __module_name__::sort();
    }

    public function state()
    {
        __module_name__::state(request()->all());
    }

    public function table()
    {
        return __module_name__::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id, false));
    }
}
