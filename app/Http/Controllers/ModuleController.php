<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\DataSource;
use App\Models\Module;
use App\Models\Content;
use Gate;
use Request;
use Response;

class ModuleController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        if (Gate::denies('@module')) {
            $this->middleware('deny403');
        }

        //获取当前模型ID
        $module_id = Request::get('module_id') ?: 0;

        return view('admin.modules.index', compact('module_id'));
    }

    public function create()
    {
        return view('admin.modules.create');
    }

    public function store(ModelRequest $request)
    {
        $input = $request->all();

        $ret = Module::insert($input);
        if (!$ret) {
            redirect()->back()->withInput();
        }

        return redirect('/admin/modules');
    }

    public function edit($id)
    {
        $module = Module::find($id);

        if (empty($module)) {
            \Session::flash('flash_warning', '无此记录');

            return redirect('/admin/modules');
        }

        return view('admin.modules.edit', compact('module'));
    }


    public function update($id, ModuleRequest $request)
    {
        $input = $request->all();

        $ret = Module::modify($id, $input);
        if (!$ret) {
            redirect()->back()->withInput();
        }

        \Session::flash('flash_success', '修改成功!');
        return redirect('/admin/modules');
    }

    public function save($id)
    {
        $category = Module::find($id);

        if (empty($category)) {
            return;
        }

        $category->update(Request::all());
    }

    public function destroy($id)
    {
        $category = Module::find($id);

        if ($category == null) {
            \Session::flash('flash_warning', '无此记录');
            return;
        }

        $child = Module::where('parent_id', $id)
            ->first();
        if (!empty($child)) {
            \Session::flash('flash_warning', '此栏目有子栏目,不允许删除该栏目');
            return;
        }

        $content = Content::where('category_id', $id)
            ->first();
        if (!empty($content)) {
            \Session::flash('flash_warning', '此栏目已有内容,不允许删除该栏目');
            return;
        }

        $category->delete();
        \Session::flash('flash_success', '删除成功');
    }

    public function table()
    {
        return Module::table();
    }

    public function fields()
    {
        $module = Module::find(1);

        $module->fields->transform(function ($field) {
            $attributes = $field->getAttributes();
            $attributes['type_name'] = $field->typeName();
            $attributes['editor_type_name'] = $field->editorTypeName();
            $attributes['column_align_name'] = $field->columnAlignName();
            return $attributes;
        });
        $ds = new DataSource();
        $ds->data = $module->fields;

        return Response::json($ds);
    }

    public function lists($category_id)
    {
        $category = Module::find($category_id);
        if (empty($category)) {
            abort(404);
        }
        return view("admin.templates.modules.list", compact('category'));
    }
}

