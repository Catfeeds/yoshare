<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Models\Feature;
use App\Models\Category;
use App\Models\Content;
use App\Models\Module;
use App\Models\Site;
use App\Models\UserLog;
use Gate;
use Request;
use Response;

/**
 * 专题
 */
class FeatureController extends Controller
{
    protected $base_url = '/admin/features';
    protected $view_path = 'admin.features';
    protected $module;

    public function __construct()
    {
        $module = Module::where('name', 'Feature')->first();
        $this->module = Module::transform($module->id);
    }

    public function show($id)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $feature = Feature::find($id);
        if (empty($feature)) {
            return abort(404);
        }

        return view('themes.' . $site->theme->name . '.features.detail', ['site' => $site, 'feature' => $feature]);
    }

    public function slug($slug)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $feature = Feature::where('slug', $slug)
            ->first();
        if (empty($feature)) {
            return abort(404);
        }

        return view('themes.' . $site->theme->name . '.features.detail', ['site' => $site, 'feature' => $feature]);
    }

    public function lists()
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $features = Feature::where('state', Feature::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $site->theme->name . '.features.index', ['site' => $site, 'module' => $this->module, 'features' => $features]);
    }

    public function category($category_id)
    {
        $category = Category::find($category_id);
        if (empty($category)) {
            return abort(404);
        }

        $features = Feature::where('category_id', $category_id)
            ->where('state', Feature::STATE_PUBLISHED)
            ->orderBy('top', 'desc')
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $category->site->theme->name . '.features.category', ['site' => $category->site, 'category' => $category, 'features' => $features]);
    }

    public function index()
    {
        if (Gate::denies('@feature')) {
            return abort(403);
        }

        return view($this->view_path . '.index', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@feature-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        return view('admin.contents.create', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@feature-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $feature = call_user_func([$this->module->model_class, 'find'], $id);

        return view('admin.contents.edit', ['module' => $this->module, 'content' => $feature, 'base_url' => $this->base_url, 'back_url' => $this->base_url . '?category_id=' . $feature->category_id]);
    }

    public function store()
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $feature = Content::stores($this->module, $input);

        event(new UserLogEvent(UserLog::ACTION_CREATE . '专题', $feature->id, $this->module->model_class));

        \Session::flash('flash_success', '添加成功');
        return redirect($this->base_url . '?category_id=' . $feature->category_id);
    }

    public function update($id)
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $feature = Content::updates($this->module, $id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '专题', $feature->id, $this->module->model_class));

        \Session::flash('flash_success', '修改成功!');
        return redirect($this->base_url . '?category_id=' . $feature->category_id);
    }

    public function comments($refer_id)
    {
        $refer_type = $this->module->model_class;
        return view('admin.comments.list', compact('refer_id', 'refer_type'));
    }

    public function save($id)
    {
        $feature = Feature::find($id);

        if (empty($feature)) {
            return;
        }

        $feature->update(Request::all());
    }

    public function sort()
    {
        return Feature::sort();
    }

    public function state()
    {
        $input = request()->all();
        Feature::state($input);

        $ids = $input['ids'];
        $stateName = Feature::getStateName($input['state']);

        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '专题' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }
    }

    public function table()
    {
        return Feature::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id, false));
    }
}
