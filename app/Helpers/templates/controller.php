<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Module;
use App\Models\__module_name__;
use Gate;
use Request;

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

    public function index()
    {
        if (Gate::denies('@__permission__')) {
            $this->middleware('deny403');
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

        $item = call_user_func([$this->module->model_class, 'find'], $id);

        return view('admin.contents.edit', ['module' => $this->module, 'content' => $item, 'base_url' => $this->base_url]);
    }

    public function store()
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Content::stores($this->module, $input);

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

        Content::updates($this->module, $id, $input);

        \Session::flash('flash_success', '修改成功!');
        return redirect($this->base_url);
    }

    public function save($id)
    {
        $item = __module_name__::find($id);

        if (empty($item)) {
            return;
        }

        $item->update(Request::all());
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
}
