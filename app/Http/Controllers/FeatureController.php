<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Models\Feature;
use App\Models\Category;
use App\Models\Item;
use App\Models\Module;
use App\Models\Site;
use App\Models\UserLog;
use App\Models\DataSource;
use Auth;
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
        $input['site_id'] = Auth::user()->site_id;
        $input['user_id'] = Auth::user()->id;

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $feature = Feature::stores($input);

        //保存图片集、音频集、视频集
        if (!empty($feature)) {
            if (isset($input['images'])) {
                Item::sync(Item::TYPE_IMAGE, $feature, $input['images']);

            }

            if (isset($input['audios'])) {
                Item::sync(Item::TYPE_AUDIO, $feature, $input['audios']);
            }

            if (isset($input['videos'])) {
                Item::sync(Item::TYPE_VIDEO, $feature, $input['videos']);
            }
        }

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

        $feature = Feature::updates($id, $input);

        //保存图片集、音频集、视频集
        if (!empty($feature)) {
            if (isset($input['images'])) {
                Item::sync(Item::TYPE_IMAGE, $feature, $input['images']);

            }

            if (isset($input['audios'])) {
                Item::sync(Item::TYPE_AUDIO, $feature, $input['audios']);
            }

            if (isset($input['videos'])) {
                Item::sync(Item::TYPE_VIDEO, $feature, $input['videos']);
            }
        }

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

    public function columnTable($category_id = 0, $time = 0)
    {
        if (!empty($time)) {
            $category_id = Category::CATEGORY_PARENT_ID;
            $firstday = date('Y-m-d', strtotime($time . '01'));
            $lastday = date("Y-m-d", strtotime("$firstday 1 month -1 day"));

            $categories = Category::owns()
                ->where('created_at', '<', $lastday)
                ->where('created_at', '>', $firstday)
                ->where('parent_id', $category_id)
                ->where('type', Category::TYPE_FEATURE)
                ->orderBy('sort')
                ->get();
        } else {
            $first = Category::where('type', Category::TYPE_FEATURE)
                ->orderBy('created_at', 'desc')
                ->first();
            $time = date('Ym', strtotime($first->created_at));
            $firstday = date('Y-m-d', strtotime($time . '01'));
            $lastday = date("Y-m-d", strtotime("$firstday 1 month -1 day"));

            $categories = Category::owns()
                ->where('created_at', '<', $lastday)
                ->where('created_at', '>', $firstday)
                ->where('parent_id', $category_id)
                ->where('type', Category::TYPE_FEATURE)
                ->orderBy('sort')
                ->get();
        }

        $categories->transform(function ($category) {
            return [
                'id' => $category->id,
                'code' => $category->code,
                'name' => $category->name,
                'module_title' => $category->module->title,
                'likes' => $category->likes,
                'parent_id' => $category->parent_id,
                'slug' => $category->slug,
                'desc' => $category->description,
                'state_name' => $category->stateName(),
                'sort' => $category->sort,
            ];
        });

        $ds = new DataSource();
        $ds->data = $categories;

        return Response::json($ds);
    }

    public function categories()
    {
        return Response::json(Feature::tree('', 0, $this->module->id, true));
    }

    public function column()
    {
        if (Gate::denies('@feature-column')) {
            $this->middleware('deny403');
        }

        //获取当前栏目ID
        $category_id = Request::get('category_id') ?: 0;
        $time = Request::get('time') ?: null;

        return view('admin.features.column', compact('category_id', 'time'));
    }

    public function columnCreate($category_id)
    {
        $modules = Module::where('state', Module::STATE_ENABLE)
            ->where('name', 'Feature')
            ->pluck('title', 'id')
            ->toArray();

        $type = Category::TYPE_FEATURE;

        return view('admin.features.create', compact('category_id', 'modules', 'type'));
    }

    public function columnEdit($id)
    {
        $category = Category::find($id);

        if (empty($category)) {
            \Session::flash('flash_warning', '无此记录');

            return redirect('/admin/features');
        }
        $modules = Module::where('state', Module::STATE_ENABLE)
            ->pluck('title', 'id')
            ->toArray();

        $type = Category::TYPE_FEATURE;

        return view('admin.features.edit', compact('category', 'modules', 'type'));
    }
}
