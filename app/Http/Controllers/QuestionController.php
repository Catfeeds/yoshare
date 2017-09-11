<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Content;
use App\Models\Module;
use App\Models\Question;
use App\Models\Site;
use App\Models\Comment;
use Gate;
use Request;
use Response;
use Auth;

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

        return view('themes.' . $site->theme->name . '.questions.detail', ['site' => $site, 'question' => $question]);
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

        return view('themes.' . $site->theme->name . '.questions.detail', ['site' => $site, 'question' => $question]);
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

        return view('themes.' . $site->theme->name . '.questions.index', ['site' => $site, 'module' => $this->module, 'questions' => $questions]);
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

    public function comment($id)
    {
        return view('admin.comments.question', compact('id'));
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

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id, false));
    }

    public function reply($id)
    {
        $site_id = Auth::user()->site_id;
        $commentContent = Request::get('content');
        $question = Question::find($id);

        $comment = $question->comments()->create([
            'site_id' => $site_id,
            'refer_id' => $id,
            'refer_type' => Comment::TYPE_QUESTION,
            'content' => $commentContent,
            'ip' => Request::getClientIp(),
            'state' => Comment::STATE_PASSED,
            'user_id' => Auth::user()->id,
        ]);

        if($question){
            $question->state = Question::STATE_PUBLISHED;
            $question->save();
        }

        return Response::json([
            'status_code' => 200,
            'message' => 'success',
            'data' => $id,
        ]);
    }
}