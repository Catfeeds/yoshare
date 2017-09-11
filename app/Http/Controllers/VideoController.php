<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Module;
use App\Models\Video;
use App\Models\Site;
use Gate;
use Request;

/**
 * 视频
 */
class VideoController extends Controller
{
    protected $base_url = '/admin/videos';
    protected $view_path = 'admin.videos';
    protected $module;

    public function __construct()
    {
        $this->module = Module::transform(Video::MODULE_ID);
    }

    public function show($id)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $video = Video::find($id);
        if (empty($video)) {
            return abort(404);
        }

        return view('themes.' . $site->theme . '.videos.detail', ['site' => $site, 'video' => $video]);
    }

    public function slug($slug)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $video = Video::where('slug', $slug)
            ->first();
        if (empty($video)) {
            return abort(404);
        }

        return view('themes.' . $site->theme . '.videos.detail', ['site' => $site, 'video' => $video]);
    }

    public function lists()
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $videos = Video::where('state', Video::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $site->theme . '.videos.index', ['site' => $site, 'module' => $this->module, 'videos' => $videos]);
    }

    public function index()
    {
        if (Gate::denies('@video')) {
            return abort(403);
        }

        return view($this->view_path . '.index', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@video-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        return view('admin.contents.create', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@video-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $video = call_user_func([$this->module->model_class, 'find'], $id);

        return view('admin.contents.edit', ['module' => $this->module, 'content' => $video, 'base_url' => $this->base_url]);
    }

    public function store()
    {
        $input = Request::all();

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Content::stores($this->module, $input);

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

        Content::updates($this->module, $id, $input);

        \Session::flash('flash_success', '修改成功!');
        return redirect($this->base_url);
    }

    public function save($id)
    {
        $video = Video::find($id);

        if (empty($video)) {
            return;
        }

        $video->update(Request::all());
    }

    public function sort()
    {
        return Video::sort();
    }

    public function state()
    {
        Video::state(request()->all());
    }

    public function table()
    {
        return Video::table();
    }
}
