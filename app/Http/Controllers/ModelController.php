<?php

namespace App\Http\Controllers;

use App\DataSource;
use App\Http\Requests\ModelRequest;
use App\Models\Category;
use App\Models\Model;
use App\Models\Content;
use DB;
use Gate;
use Request;
use Response;

class ModelController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        if (Gate::denies('@model')) {
            $this->middleware('deny403');
        }

        //获取当前栏目ID
        $model_id = Request::get('model') ?: 0;

        return view('models.index', compact('model_id'));
    }

    public function create($category_id)
    {
        return view('models.create', compact('category_id'));
    }

    public function store(ModelRequest $request)
    {
        $input = Request::all();
        $category_id = $input['category_id'];

        $sort = Model::select(DB::raw('max(sort) as max'))
            ->where('parent_id', '=', $category_id)
            ->first()->max;

        $sort += 1;

        $input['sort'] = $sort;
        $input['parent_id'] = $category_id;
        $input['site_id'] = \Auth::user()->site_id;

        Model::create($input);

        $url = '/models?category_id=' . $category_id;
        \Session::flash('flash_success', '添加成功');
        return redirect($url);
    }

    public function edit($id)
    {
        $category = Model::find($id);

        if (empty($category)) {
            \Session::flash('flash_warning', '无此记录');

            return redirect('/models');
        }

        return view('models.edit', compact('category'));
    }


    public function update($id, ModelRequest $request)
    {
        $category = Model::find($id);

        if ($category == null) {
            \Session::flash('flash_warning', '无此记录');
            return redirect()->to($this->getRedirectUrl())
                ->withInput($request->input());
        }

        $input = Request::all();

        $category->update($input);

        $category_id = $category->parent_id > 0 ? $category->parent_id : $category->id;

        \Session::flash('flash_success', '修改成功!');
        return redirect('/models?category_id=' . $category_id);
    }

    public function save($id)
    {
        $category = Model::find($id);

        if (empty($category)) {
            return;
        }

        $category->update(Request::all());
    }

    public function destroy($id)
    {
        $category = Model::find($id);

        if ($category == null) {
            \Session::flash('flash_warning', '无此记录');
            return;
        }

        $child = Model::where('parent_id', $id)
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

    public function tree()
    {
        return Response::json(Model::tree());
    }

    public function lists($category_id)
    {
        $category = Model::find($category_id);
        if (empty($category)) {
            abort(404);
        }
        return view("templates.models.list", compact('category'));
    }

    public function table($category_id)
    {
        $fields = config('site.model.1.fields');
        $fields = json_decode(json_encode($fields));
        $fields = array_sort($fields, function ($field) {
            return $field->editor->index;
        });
        $tabs = [
            [
                'name' => 'info',
                'alias' => '基本信息',
                'fields' => array_values(array_filter($fields, function ($field) {
                    return $field->editor->show && $field->editor->tab == 'info';
                }))
            ],
            [
                'name' => 'content',
                'alias' => '正文',
                'fields' => array_values(array_filter($fields, function ($field) {
                    return $field->editor->show && $field->editor->tab == 'content';
                }))
            ]
        ];
        $tabs = json_decode(json_encode($tabs));

        $ds = new DataSource();
        $ds->data = [];
        $sort = 0;
        foreach ($tabs as $tab) {
            foreach ($tab->fields as $field) {
                $ds->data[] = [
                    'sort' => $sort++,
                    'name' => $field->name,
                    'alias' => $field->alias,
                    'type' => $field->type,
                    'length' => $field->length,
                    'system' => $field->system,
                    'required' => $field->editor->required,
                    'table_show' => $field->table->show,
                    'table_align' => $field->table->align,
                    'table_width' => $field->table->width,
                    'table_formatter' => $field->table->formatter,
                    'table_editable' => $field->table->editable,
                ];
            }
        }

        return Response::json($ds);
    }

    private function getChildren($all, $parent)
    {
        $result = array();
        foreach ($all as $item) {
            if ($item->parent_id == $parent->id) {
                $result[count($result)] = $item;
                $child = $this->getChildren($all, $item);
                $result = array_merge($result, $child);
            }
        }
        return $result;
    }

}

