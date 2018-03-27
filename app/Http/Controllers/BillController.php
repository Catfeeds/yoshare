<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Jobs\PublishPage;
use App\Models\Bill;
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
 * 账单表
 */
class BillController extends Controller
{
    protected $base_url = '/admin/bills';
    protected $view_path = 'admin.bills';
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('name', 'Bill')->first();
    }

    public function show(Domain $domain, $id)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $bill = Bill::find($id);
        if (empty($bill)) {
            return abort(404);
        }
        $bill->incrementClick();

        return view('themes.' . $domain->theme->name . '.bills.detail', ['site' => $domain->site, 'bill' => $bill]);
    }

    public function slug(Domain $domain, $slug)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $bill = Bill::where('slug', $slug)
            ->first();
        if (empty($bill)) {
            return abort(404);
        }
        $bill->incrementClick();

        return view('themes.' . $domain->theme->name . '.bills.detail', ['site' => $domain->site, 'bill' => $bill]);
    }

    public function lists(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $bills = Bill::where('state', Bill::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $domain->theme->name . '.bills.index', ['site' => $domain->site, 'module' => $this->module, 'bills' => $bills]);
    }

    public function index()
    {
        if (Gate::denies('@bill')) {
            return abort(403);
        }

        $module = Module::transform($this->module->id);

        return view($this->view_path . '.index', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@bill-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        return view('admin.contents.create', ['module' => $module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@bill-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $module = Module::transform($this->module->id);

        $bill = call_user_func([$this->module->model_class, 'find'], $id);
        $bill->images = null;
        $bill->videos = null;
        $bill->audios = null;
        $bill->tags = $bill->tags()->pluck('name')->toArray();

        return view('admin.contents.edit', ['module' => $module, 'content' => $bill, 'base_url' => $this->base_url]);
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

        $bill = Bill::stores($input);

        event(new UserLogEvent(UserLog::ACTION_CREATE . '账单表', $bill->id, $this->module->model_class));

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

        $bill = Bill::updates($id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '账单表', $bill->id, $this->module->model_class));

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
        $bill = Bill::find($id);

        if (empty($bill)) {
            return;
        }

        $bill->update(Request::all());
    }

    public function sort()
    {
        return Bill::sort();
    }

    public function top($id)
    {
        $bill = Bill::find($id);
        $bill->top = !$bill->top;
        $bill->save();
    }

    public function tag($id)
    {
        $tag = request('tag');
        $bill = Bill::find($id);
        if ($bill->tags()->where('name', $tag)->exists()) {
            $bill->tags()->where('name', $tag)->delete();
        } else {
            $bill->tags()->create([
                'site_id' => $bill->site_id,
                'name' => $tag,
                'sort' => strtotime(Carbon::now()),
            ]);
        }
    }

    public function state()
    {
        $input = request()->all();
        Bill::state($input);

        $ids = $input['ids'];
        $stateName = Bill::getStateName($input['state']);

        //记录日志
        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '账单表' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }

        //发布页面
        $site = auth()->user()->site;
        if ($input['state'] == Bill::STATE_PUBLISHED) {
            foreach ($ids as $id) {
                $this->dispatch(new PublishPage($site, $this->module, $id));
            }
        }
    }

    public function table()
    {
        return Bill::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id));
    }

    public function buildBillNum()
    {
        //生成流水号并保存至$file文件中
        $file = "bill.txt";
        if(!file_exists($file)){
            if($handle = fopen($file,"a+")){
                $textTime = date("mdY");
                $num_order_new = str_pad($textTime,9,'0',STR_PAD_RIGHT);
                fwrite($handle,$num_order_new);
                $content = $num_order_new;
                fclose($handle);
            }else{
                $msg = '流水号创建失败！!';// TODO:
                $this->responseError($msg, 404);
            }
        }else{
            if($handle = fopen($file,"r+")){
                $content = file_get_contents($file);
                $new = $content+1;
                if(!fwrite($handle,$new)){
                    $msg = 'ERROR!';
                    $this->responseError($msg, 500);
                }
                fclose($handle);
            }
        }

        return 'w'.$content.rand(100, 999);
    }
}
