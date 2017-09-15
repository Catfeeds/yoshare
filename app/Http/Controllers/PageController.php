<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Models\Page;
use App\Models\Category;
use App\Models\Content;
use App\Models\Module;
use App\Models\Site;
use App\Models\UserLog;
use Gate;
use Request;
use Response;

/**
 * 页面
 */
class PageController extends Controller
{
    protected $base_url = '/admin/pages';
    protected $view_path = 'admin.pages';
    protected $module;

    public function __construct()
    {
        $module = Module::where('name', 'Page')->first();
        $this->module = Module::transform($module->id);
    }

    public function show($id)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $page = Page::find($id);
        if (empty($page)) {
            return abort(404);
        }

        return view('themes.' . $site->theme->name . '.pages.detail', ['site' => $site, 'page' => $page]);
    }

    public function slug($slug)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $page = Page::where('slug', $slug)
            ->first();
        if (empty($page)) {
            return abort(404);
        }

        return view('themes.' . $site->theme->name . '.pages.detail', ['site' => $site, 'page' => $page]);
    }

    public function lists()
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $pages = Page::where('state', Page::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $site->theme->name . '.pages.index', ['site' => $site, 'module' => $this->module, 'pages' => $pages]);
    }

    public function index()
    {
        if (Gate::denies('@page')) {
            return abort(403);
        }

        return view($this->view_path . '.index', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@page-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        return view('admin.contents.create', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@page-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $page = call_user_func([$this->module->model_class, 'find'], $id);

        return view('admin.contents.edit', ['module' => $this->module, 'content' => $page, 'base_url' => $this->base_url]);
    }

    public function store()
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $page = Content::stores($this->module, $input);

        event(new UserLogEvent(UserLog::ACTION_CREATE . '页面', $page->id, $this->module->model_class));

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

        $page = Content::updates($this->module, $id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '页面', $page->id, $this->module->model_class));

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
        $page = Page::find($id);

        if (empty($page)) {
            return;
        }

        $page->update(Request::all());
    }

    public function sort()
    {
        return Page::sort();
    }

    public function state()
    {
        $input = request()->all();
        Page::state($input);

        $ids = $input['ids'];
        $stateName = Page::getStateName($input['state']);

        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '页面' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }
    }

    public function table()
    {
        return Page::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id));
    }
}
