<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Jobs\PublishPage;
use App\Models\Goods;
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
 * 商品
 */
class GoodsController extends Controller
{
    protected $base_url = '/admin/goods';
    protected $view_path = 'admin.goods';
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('name', 'Goods')->first();
    }

    public function show(Domain $domain, $id)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $goods = Goods::find($id);

        if (empty($goods)) {
            return abort(404);
        }
        $goods->incrementClick();

        $system['mark'] = Domain::MARK_DETAIL;
        return view('themes.' . $domain->theme->name . '.goods.detail', ['site' => $domain->site, 'goods' => $goods, 'system' => $system]);
    }

    public function slug(Domain $domain, $slug)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $good = Goods::where('slug', $slug)
            ->first();
        if (empty($good)) {
            return abort(404);
        }
        $good->incrementClick();

        return view('themes.' . $domain->theme->name . '.goods.detail', ['site' => $domain->site, 'good' => $good]);
    }

    public function lists(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $goods = Goods::where('state', Goods::STATE_PUBLISHED)
            ->orderBy('top', 'desc')
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $domain->theme->name . '.goods.index', ['site' => $domain->site, 'module' => $this->module, 'goods' => $goods]);
    }

    public function category(Domain $domain, $category_id)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $lists = Category::where('parent_id', Category::GOODS_ID)->pluck( 'name','id');

        $category = Category::find($category_id);
        if (empty($category)) {
            return abort(404);
        }

        $goods = Goods::where('category_id', $category_id)
            ->where('state', Goods::STATE_PUBLISHED)
            ->orderBy('top', 'desc')
            ->orderBy('sort', 'desc')
            ->get();

        $system['mark'] = Domain::MARK_GOODS;
        return view('themes.' . $domain->theme->name . '.goods.category', ['lists' => $lists, 'category' => $category, 'goods' => $goods, 'system' => $system]);
    }

    public function index()
    {
        if (Gate::denies('@goods')) {
            return abort(403);
        }

        $module = Module::transform($this->module->id);

        return view($this->view_path . '.index', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@goods-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        return view('admin.contents.create', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@goods-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        $good = call_user_func([$this->module->model_class, 'find'], $id);
        $good->images = null;
        $good->videos = null;
        $good->audios = null;
        $good->tags = $good->tags()->pluck('name')->toArray();

        return view('admin.contents.edit', ['module' => $module, 'content' => $good, 'base_url' => $this->base_url, 'back_url' => $this->base_url . '?category_id=' . $good->category_id]);
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

        $good = Goods::stores($input);

        event(new UserLogEvent(UserLog::ACTION_CREATE . '商品', $good->id, $this->module->model_class));

        \Session::flash('flash_success', '添加成功');
        return redirect($this->base_url . '?category_id=' . $good->category_id);
    }

    public function update($id)
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $good = Goods::updates($id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '商品', $good->id, $this->module->model_class));

        \Session::flash('flash_success', '修改成功!');
        return redirect($this->base_url . '?category_id=' . $good->category_id);
    }

    public function comments($refer_id)
    {
        $refer_type = $this->module->model_class;
        return view('admin.comments.list', compact('refer_id', 'refer_type'));
    }

    public function save($id)
    {
        $good = Goods::find($id);

        if (empty($good)) {
            return;
        }

        $good->update(Request::all());
    }

    public function sort()
    {
        return Goods::sort();
    }

    public function top($id)
    {
        $good = Goods::find($id);
        $good->top = !$good->top;
        $good->save();
    }

    public function tag($id)
    {
        $tag = request('tag');
        $good = Goods::find($id);
        if ($good->tags()->where('name', $tag)->exists()) {
            $good->tags()->where('name', $tag)->delete();
        } else {
            $good->tags()->create([
                'site_id' => $good->site_id,
                'name' => $tag,
                'sort' => strtotime(Carbon::now()),
            ]);
        }
    }

    public function state()
    {
        $input = request()->all();
        Goods::state($input);

        $ids = $input['ids'];
        $stateName = Goods::getStateName($input['state']);

        //记录日志
        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '商品' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }

        //发布页面
        $site = auth()->user()->site;
        if ($input['state'] == Goods::STATE_PUBLISHED) {
            foreach ($ids as $id) {
                $this->dispatch(new PublishPage($site, $this->module, $id));
            }
        }
    }

    public function table()
    {
        return Goods::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id, false));
    }

    public function search()
    {
        $input = request()->all();

        $res = Goods::where('name', 'like', '%'.$input['name'].'%')->get();

        if($res){
            return $this->responseSuccess($res);
        }else{
            return $this->responseError('此游戏暂未上架呦，敬请期待！');
        }
    }
}
