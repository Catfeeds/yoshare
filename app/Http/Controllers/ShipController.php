<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Jobs\PublishPage;
use App\Models\Ship;
use App\Models\Category;
use App\Models\Domain;
use App\Models\Module;
use App\Models\UserLog;
use Auth;
use Carbon\Carbon;
use Gate;
use Request;
use Response;

/**
 * 物流方式
 */
class ShipController extends Controller
{
    protected $base_url = '/admin/ships';
    protected $view_path = 'admin.ships';
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('name', 'Ship')->first();
    }

    public function show(Domain $domain, $id)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $ship = Ship::find($id);
        if (empty($ship)) {
            return abort(404);
        }
        $ship->incrementClick();

        return view('themes.' . $domain->theme->name . '.ships.detail', ['site' => $domain->site, 'ship' => $ship]);
    }

    public function slug(Domain $domain, $slug)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $ship = Ship::where('slug', $slug)
            ->first();
        if (empty($ship)) {
            return abort(404);
        }
        $ship->incrementClick();

        return view('themes.' . $domain->theme->name . '.ships.detail', ['site' => $domain->site, 'ship' => $ship]);
    }

    public function lists(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $ships = Ship::where('state', Ship::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $domain->theme->name . '.ships.index', ['site' => $domain->site, 'module' => $this->module, 'ships' => $ships]);
    }

    public function index()
    {
        if (Gate::denies('@ship')) {
            return abort(403);
        }

        $module = Module::transform($this->module->id);

        return view($this->view_path . '.index', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@ship-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        return view('admin.contents.create', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@ship-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        $ship = call_user_func([$this->module->model_class, 'find'], $id);
        $ship->images = null;
        $ship->videos = null;
        $ship->audios = null;
        $ship->tags = $ship->tags()->pluck('name')->toArray();

        return view('admin.contents.edit', ['module' => $module, 'content' => $ship, 'base_url' => $this->base_url]);
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

        $ship = Ship::stores($input);

        event(new UserLogEvent(UserLog::ACTION_CREATE . '物流方式', $ship->id, $this->module->model_class));

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

        $ship = Ship::updates($id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '物流方式', $ship->id, $this->module->model_class));

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
        $ship = Ship::find($id);

        if (empty($ship)) {
            return;
        }

        $ship->update(Request::all());
    }

    public function sort()
    {
        return Ship::sort();
    }

    public function top($id)
    {
        $ship = Ship::find($id);
        $ship->top = !$ship->top;
        $ship->save();
    }

    public function tag($id)
    {
        $tag = request('tag');
        $ship = Ship::find($id);
        if ($ship->tags()->where('name', $tag)->exists()) {
            $ship->tags()->where('name', $tag)->delete();
        } else {
            $ship->tags()->create([
                'site_id' => $ship->site_id,
                'name' => $tag,
                'sort' => strtotime(Carbon::now()),
            ]);
        }
    }

    public function state()
    {
        $input = request()->all();
        Ship::state($input);

        $ids = $input['ids'];
        $stateName = Ship::getStateName($input['state']);

        //记录日志
        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '物流方式' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }

        //发布页面
        $site = auth()->user()->site;
        if ($input['state'] == Ship::STATE_PUBLISHED) {
            foreach ($ids as $id) {
                $this->dispatch(new PublishPage($site, $this->module, $id));
            }
        }
    }

    public function table()
    {
        return Ship::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id));
    }
}
