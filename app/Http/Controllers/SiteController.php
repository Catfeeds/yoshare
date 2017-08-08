<?php

namespace App\Http\Controllers;

use App\Models\DataSource;
use App\Http\Requests\SiteRequest;
use App\Models\Site;
use Request;
use Response;
use Gate;
use Auth;

class SiteController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        if (Gate::denies('@site')) {
            $this->middleware('deny403');
        }

        return view('admin.sites.index');
    }

    public function edit($id)
    {
        $site = Site::find($id);

        if ($site == null) {

            \Session::flash('flash_warning', '无此记录');
            return redirect('/admin/sites');
        }

        return view('admin.sites.edit', compact('site'));
    }

    public function update($id, SiteRequest $request)
    {
        $sites = Site::find($id);

        if ($sites == null) {
            \Session::flash('flash_warning', '无此记录');
            return redirect()->to($this->getRedirectUrl())
                ->withInput($request->input());
        }
        $input = Request::all();
        $input['username'] = Auth::user()->name;
        $sites->update($input);

        \Session::flash('flash_success', '修改成功!');
        return redirect('/admin/sites');
    }

    public function destroy($id)
    {
        $site = Site::find($id);
        if ($site == null) {
            \Session::flash('flash_warning', '无此记录');
            return;
        }
        $site->delete();
        \Session::flash('flash_success', '删除成功');
    }

    public function create()
    {
        return view('admin.sites.create');
    }

    public function store(SiteRequest $request)
    {
        $input = Request::all();
        $input['username'] = Auth::user()->name;
        Site::create($input);
        \Session::flash('flash_success', '添加成功');
        return redirect('/admin/sites');
    }

    public function table()
    {
        $sites = Site::all();

        $sites->transform(function ($site) {
            return [
                'id' => $site->id,
                'name' => $site->name,
                'company' => $site->company,
                'username' => $site->username,
                'app_key' => $site->app_key,
                'master_secret' => $site->master_secret,
                'created_at' => $site->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $site->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        $ds = new DataSource();
        $ds->data = $sites;

        return Response::json($ds);
    }
}
