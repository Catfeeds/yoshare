<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Content;
use App\Models\Module;
use Gate;
use Request;
use Response;

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
    protected $base_url = '/admin/articles';
    protected $view_path = 'admin.articles';
    protected $module;

    public function __construct()
    {
        $this->module = Module::transform(Article::MODULE_ID);
    }

    public function index()
    {
        if (Gate::denies('@article')) {
            $this->middleware('deny403');
        }

        return view($this->view_path . '.index', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@article-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        //用于取消时跳转
        $back_url = $this->base_url . '?category_id=' . Request::get('category_id');

        return view('admin.contents.create', ['module' => $this->module, 'base_url' => $this->base_url, 'back_url' => $back_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@article-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $content = call_user_func([$this->module->model_class, 'find'], $id);

        //用于取消时跳转
        $back_url = $this->base_url . '?category_id=' . $content->category_id;

        return view('admin.contents.edit', ['module' => $this->module, 'content' => $content, 'base_url' => $this->base_url, 'back_url' => $back_url]);
    }

    public function store(ArticleRequest $request)
    {
        $input = $request->all();

        $content = Content::stores($this->module, $input);

        \Session::flash('flash_success', '添加成功');
        return redirect($this->base_url . '?category_id=' . $content->category_id);
    }

    public function update($id, ArticleRequest $request)
    {
        $input = $request->all();

        $content = Content::updates($this->module, $id, $input);

        \Session::flash('flash_success', '修改成功!');
        return redirect($this->base_url . '?category_id=' . $content->category_id);
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

    public function categories()
    {
        return Response::json(Category::tree('', 0, Article::MODULE_ID));
    }
}
