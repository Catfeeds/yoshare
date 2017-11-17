<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Jobs\PublishPage;
use App\Models\Address;
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
 * 地址
 */
class AddressController extends Controller
{
    protected $base_url = '/admin/addresses';
    protected $view_path = 'admin.addresses';
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('name', 'Address')->first();
    }

    public function slug(Domain $domain, $slug)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $address = Address::where('slug', $slug)
            ->first();
        if (empty($address)) {
            return abort(404);
        }

        return view('themes.' . $domain->theme->name . '.addresses.detail', ['site' => $domain->site, 'address' => $address]);
    }

    public function lists(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        //$addresses = $user->addresses(); TODO
        $addresses = '';
        $mark = 'member';
        $title = '管理收货地址';

        return view('themes.' . $domain->theme->name . '.address.index', ['title' => $title, 'module' => $this->module, 'addresses' => $addresses, 'mark' => $mark]);
    }

    public function index()
    {
        if (Gate::denies('@address')) {
            return abort(403);
        }

        $module = Module::transform($this->module->id);

        return view($this->view_path . '.index', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function create(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $title = '添加收货地址';

        return view('themes.'. $domain->theme->name .'.address.create', ['title' => $title]);
    }

    public function edit($id)
    {
        if (Gate::denies('@address-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        $address = call_user_func([$this->module->model_class, 'find'], $id);
        $address->images = null;
        $address->videos = null;
        $address->audios = null;
        $address->tags = $address->tags()->pluck('name')->toArray();

        return view('admin.contents.edit', ['module' => $module, 'content' => $address, 'base_url' => $this->base_url]);
    }

    public function store()
    {
        $input = Request::all();
        $input['site_id'] = Auth::member()->site_id;
        $input['member'] = Auth::member()->id;

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $address = Address::stores($input);

        event(new UserLogEvent(UserLog::ACTION_CREATE . '地址', $address->id, $this->module->model_class));

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

        $address = Address::updates($id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '地址', $address->id, $this->module->model_class));

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
        $address = Address::find($id);

        if (empty($address)) {
            return;
        }

        $address->update(Request::all());
    }

    public function sort()
    {
        return Address::sort();
    }

    public function top($id)
    {
        $address = Address::find($id);
        $address->top = !$address->top;
        $address->save();
    }

    public function tag($id)
    {
        $tag = request('tag');
        $address = Address::find($id);
        if ($address->tags()->where('name', $tag)->exists()) {
            $address->tags()->where('name', $tag)->delete();
        } else {
            $address->tags()->create([
                'site_id' => $address->site_id,
                'name' => $tag,
                'sort' => strtotime(Carbon::now()),
            ]);
        }
    }

    public function state()
    {
        $input = request()->all();
        Address::state($input);

        $ids = $input['ids'];
        $stateName = Address::getStateName($input['state']);

        //记录日志
        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '地址' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }

        //发布页面
        $site = auth()->user()->site;
        if ($input['state'] == Address::STATE_PUBLISHED) {
            foreach ($ids as $id) {
                $this->dispatch(new PublishPage($site, $this->module, $id));
            }
        }
    }

    public function table()
    {
        return Address::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id));
    }
}
