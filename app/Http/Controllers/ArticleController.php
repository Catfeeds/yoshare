<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Content;
use App\Models\Module;
use App\Models\Site;
use Gate;
use Request;
use Response;

/**
 * 文章
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

    public function show($id)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $article = Article::find($id);
        if (empty($article)) {
            return abort(404);
        }

        return view('themes.' . $site->theme->name . '.articles.detail', ['site' => $site, 'article' => $article]);
    }

    public function slug($slug)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $article = Article::where('slug', $slug)
            ->first();
        if (empty($article)) {
            return abort(404);
        }

        return view('themes.' . $site->theme->name . '.articles.detail', ['site' => $site, 'article' => $article]);
    }

    public function lists()
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $articles = Article::where('state', Article::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $site->theme->name . '.articles.index', ['site' => $site, 'module' => $this->module, 'articles' => $articles]);
    }

    public function category($category_id)
    {
        $category = Category::find($category_id);
        if (empty($category)) {
            return abort(404);
        }

        $articles = Article::where('category_id', $category_id)
            ->where('state', Article::STATE_PUBLISHED)
            ->orderBy('top', 'desc')
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $category->site->theme->name . '.articles.category', ['site' => $category->site, 'category' => $category, 'articles' => $articles]);
    }

    public function index()
    {
        if (Gate::denies('@article')) {
            return abort(403);
        }

        return view($this->view_path . '.index', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@article-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        return view('admin.contents.create', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@article-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $article = call_user_func([$this->module->model_class, 'find'], $id);

        return view('admin.contents.edit', ['module' => $this->module, 'content' => $article, 'base_url' => $this->base_url, 'back_url' => $this->base_url . '?category_id=' . $article->category_id]);
    }

    public function store()
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $article = Content::stores($this->module, $input);

        \Session::flash('flash_success', '添加成功');
        return redirect($this->base_url . '?category_id=' . $article->category_id);
    }

    public function update($id)
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $article = Content::updates($this->module, $id, $input);

        \Session::flash('flash_success', '修改成功!');
        return redirect($this->base_url . '?category_id=' . $article->category_id);
    }

    public function comment($id)
    {
        return view('admin.comments.list', compact('id'));
    }

    public function save($id)
    {
        $article = Article::find($id);

        if (empty($article)) {
            return;
        }

        $article->update(Request::all());
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
        return Response::json(Category::tree('', 0, $this->module->id, false));
    }
}
