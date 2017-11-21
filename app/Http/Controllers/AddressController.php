<?php

namespace App\Http\Controllers;

use App\Events\MemberLogEvent;
use App\Events\UserLogEvent;
use App\Jobs\PublishPage;
use App\Models\Address;
use App\Models\Category;
use App\Models\Dictionary;
use App\Models\Domain;
use App\Models\Member;
use App\Models\MemberLog;
use App\Models\Module;
use App\Models\UserLog;
use Carbon\Carbon;
use Gate;
use Request;
use Response;

/**
 * 地址
 */
class AddressController extends Controller
{
    protected $base_url = '/address/index.html';
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

        $addresses = Member::getMember()->addresses()->get();

        $mark = 'member';
        $title = '管理收货地址';
        $back = '/member';

        return view('themes.' . $domain->theme->name . '.address.index', ['title' => $title, 'back' => $back, 'addresses' => $addresses, 'mark' => $mark]);
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
        $back = '/address/index.html';
        //查询字典中的省份
        $provinces = Dictionary::where('parent_id', Address::COUNTRY_ID)
            ->pluck('name', 'id')
            ->toarray();
        //查询北京市
        $cities = Dictionary::where('parent_id', Address::PROVINCE_BJ)
            ->pluck('name', 'id')
            ->toarray();
        //查询北京市所管辖的区
        $towns = Dictionary::where('parent_id', Address::CITY_BJ)
            ->pluck('name', 'id')
            ->toarray();

        return view('themes.' . $domain->theme->name . '.address.create', ['title' => $title, 'back' => $back, 'provinces' => $provinces, 'cities' => $cities, 'towns' => $towns]);
    }

    public function region()
    {
        $input = Request::all();

        $regions = Dictionary::where('parent_id', $input['parent_id'])
            ->where('code', $input['code'])
            ->get();
        return $regions;
    }

    public function edit(Domain $domain, $id)
    {
        $title = '编辑收货地址';
        $back = '/address/index.html';

        $address = Address::find($id);
        //查询字典中的省份
        $provinces = Dictionary::where('parent_id', Address::COUNTRY_ID)
            ->pluck('name', 'id')
            ->toarray();
        //查询同级城市
        $cities = Dictionary::where('parent_id', $address->province)
            ->pluck('name', 'id')
            ->toarray();
        //查询同级乡镇
        $towns = Dictionary::where('parent_id', $address->city)
            ->pluck('name', 'id')
            ->toarray();

        return view('themes.' . $domain->theme->name . '.address.edit', ['title' => $title, 'back' => $back, 'address' => $address, 'provinces' => $provinces, 'cities' => $cities, 'towns' => $towns]);
    }

    public function store()
    {
        $input = Request::all();
        $member = Member::getMember();

        $input['site_id'] = $member->site_id;
        //查询是否有默认地址，无则设置，有则设置is_default=0
        $address = Address::where('member_id', $member->id)
            ->where('is_default', Address::IS_DEFAULT)
            ->first();
        if ($address) {
            $input['is_default'] = Address::NO_DEFAULT;
        } else {
            $input['is_default'] = Address::IS_DEFAULT;
        }

        $address = Address::stores($input);

        //TODO
        //event(new MemberLogEvent(MemberLog::ACTION_CREATE . '地址', $address->id, $this->module->model_class));

        return redirect($this->base_url);
    }

    public function update($id)
    {
        $input = Request::all();

        $address = Address::updates($id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '地址', $address->id, $this->module->model_class));

        return redirect($this->base_url);
    }

    public function destroy($id)
    {
        $address = Address::find($id);
        $result = $address->delete();
        if ($result) {
            return redirect($this->base_url);
        } else {
            //TODO
        }
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

    public function setDefault($id)
    {
        //取消原本默认地址
        $default = Address::where('is_default', Address::IS_DEFAULT)->first();
        $default->is_default = Address::NO_DEFAULT;
        $default->save();
        $address = Address::find($id);
        $address->is_default = !$address->top;
        $address->save();

        return redirect($this->base_url);
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
