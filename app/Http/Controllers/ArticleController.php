<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Models\Article;
use App\Models\Category;
use App\Models\Item;
use App\Models\Module;
use App\Models\Site;
use App\Models\UserLog;
use Auth;
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
        $module = Module::where('name', 'Article')->first();
        $this->module = Module::transform($module->id);
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
        $input['site_id'] = Auth::user()->site_id;
        $input['user_id'] = Auth::user()->id;

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $article = Article::stores($input);

        //保存图片集、音频集、视频集
        if (!empty($article)) {
            if (isset($input['images'])) {
                Item::sync(Item::TYPE_IMAGE, $article, $input['images']);

            }

            if (isset($input['audios'])) {
                Item::sync(Item::TYPE_AUDIO, $article, $input['audios']);
            }

            if (isset($input['videos'])) {
                Item::sync(Item::TYPE_VIDEO, $article, $input['videos']);
            }
        }

        event(new UserLogEvent(UserLog::ACTION_CREATE . '文章', $article->id, $this->module->model_class));

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

        $article = Article::updates($id, $input);

        //保存图片集、音频集、视频集
        if (!empty($article)) {
            if (isset($input['images'])) {
                Item::sync(Item::TYPE_IMAGE, $article, $input['images']);

            }

            if (isset($input['audios'])) {
                Item::sync(Item::TYPE_AUDIO, $article, $input['audios']);
            }

            if (isset($input['videos'])) {
                Item::sync(Item::TYPE_VIDEO, $article, $input['videos']);
            }
        }

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '文章', $article->id, $this->module->model_class));

        \Session::flash('flash_success', '修改成功!');
        return redirect($this->base_url . '?category_id=' . $article->category_id);
    }

    public function comments($refer_id)
    {
        $refer_type = $this->module->model_class;
        return view('admin.comments.list', compact('refer_id', 'refer_type'));
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

    public function top($id)
    {
        $article = Article::find($id);
        $article->top = !$article->top;
        $article->save();
    }

    public function state()
    {
        $input = request()->all();
        Article::state($input);

        $ids = $input['ids'];
        $stateName = Article::getStateName($input['state']);

        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '文章' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }
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
