<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Module;
use Gate;
use Request;

class __controller__ extends Controller
{
    protected $base_url = '/admin/__module_path__';
    protected $view_path = 'admin.__module_path__';
    protected $module;

    public function __construct()
    {
        $this->module = Module::transform(__module_id__);
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

        //用于取消时跳转
        $back_url = $this->base_url . '?category_id=' . Request::get('category_id');

        return view('admin.contents.create', ['module' => $this->module, 'base_url' => $this->base_url, 'back_url' => $back_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@__permission__-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $content = call_user_func([$this->module->model_class, 'find'], $id);

        //用于取消时跳转
        $back_url = $this->base_url . '?category_id=' . $content->category_id;

        return view('admin.contents.edit', ['module' => $this->module, 'content' => $content, 'base_url' => $this->base_url, 'back_url' => $back_url]);
    }

    public function store()
    {
        $input = Request::all();

        $content = Content::stores($this->module, $input);

        \Session::flash('flash_success', '添加成功');
        return redirect($this->base_url . '?category_id=' . $content->category_id);
    }

    public function update($id)
    {
        $input = Request::all();

        $content = Content::updates($this->module, $id, $input);

        \Session::flash('flash_success', '修改成功!');
        return redirect($this->base_url . '?category_id=' . $content->category_id);
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
