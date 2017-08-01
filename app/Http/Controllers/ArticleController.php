<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Content;
use App\Models\Module;
use Gate;

/**
 * 复制需要修改
 * $path
 * $view
 * Module::ID
 * Request
 * 模型类名称
 */
class ArticleController extends Controller
{
    protected $path = '/admin/articles';
    protected $view = 'admin.articles';
    protected $model;

    public function __construct()
    {
        $this->model = Module::generate(Module::ID_ARTICLE);
    }

    public function index()
    {
        if (Gate::denies('@article')) {
            $this->middleware('deny403');
        }

        return view($this->view . '.index', ['model' => $this->model, 'path' => $this->path]);
    }

    public function create()
    {
        if (Gate::denies('@article-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        return view('admin.contents.create', ['model' => $this->model, 'path' => $this->path]);
    }

    public function edit($id)
    {
        if (Gate::denies('@article-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $content = call_user_func([$this->model->class, 'find'], $id);

        return view('admin.contents.edit', ['model' => $this->model, 'path' => $this->path, 'content' => $content]);
    }

    public function store(ArticleRequest $request)
    {
        $input = $request->all();
        $input['category_id'] = Category::ID_FAQ;

        Content::stores($this->model, $input);

        \Session::flash('flash_success', '添加成功');
        return redirect($this->path);
    }

    public function update($id, ArticleRequest $request)
    {
        $input = $request->all();

        Content::updates($this->model, $id, $input);

        \Session::flash('flash_success', '修改成功!');
        return redirect($this->path);
    }

    public function sort()
    {
        return Article::sort();
    }

    public function state()
    {
        Article::state(request()->all());
    }

    public function table()
    {
        return Article::table();
    }
}
