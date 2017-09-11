<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Module;
use App\Models\Question;
use App\Models\Site;
use Gate;
use Request;

/**
 * 问答
 */
class QuestionController extends Controller
{
    protected $base_url = '/admin/questions';
    protected $view_path = 'admin.questions';
    protected $module;

    public function __construct()
    {
        $this->module = Module::transform(Question::MODULE_ID);
    }

    public function show($id)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $question = Question::find($id);
        if (empty($question)) {
            return abort(404);
        }

        return view('themes.' . $site->theme . '.questions.detail', ['site' => $site, 'question' => $question]);
    }

    public function slug($slug)
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $question = Question::where('slug', $slug)
            ->first();
        if (empty($question)) {
            return abort(404);
        }

        return view('themes.' . $site->theme . '.questions.detail', ['site' => $site, 'question' => $question]);
    }

    public function lists()
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        $questions = Question::where('state', Question::STATE_PUBLISHED)
            ->orderBy('sort', 'desc')
            ->get();

        return view('themes.' . $site->theme . '.questions.index', ['site' => $site, 'module' => $this->module, 'questions' => $questions]);
    }

    public function index()
    {
        if (Gate::denies('@question')) {
            return abort(403);
        }

        return view($this->view_path . '.index', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function create()
    {
        if (Gate::denies('@question-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        return view('admin.contents.create', ['module' => $this->module, 'base_url' => $this->base_url]);
    }

    public function edit($id)
    {
        if (Gate::denies('@question-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $question = call_user_func([$this->module->model_class, 'find'], $id);

        return view('admin.contents.edit', ['module' => $this->module, 'content' => $question, 'base_url' => $this->base_url]);
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
        $question = Question::find($id);

        if (empty($question)) {
            return;
        }

        $question->update(Request::all());
    }

    public function sort()
    {
        return Question::sort();
    }

    public function state()
    {
        Question::state(request()->all());
    }

    public function table()
    {
        return Question::table();
    }
}
