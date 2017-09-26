<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Jobs\PublishPage;
use App\Models\Question;
use App\Models\Category;
use App\Models\Module;
use App\Models\Site;
use App\Models\UserLog;
use Auth;
use Carbon\Carbon;
use Gate;
use Request;
use Response;

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
        $this->module = Module::where('name', 'Question')->first();
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

        $module = Module::transform($this->module->id);

        return view($this->view_path . '.index', ['module' => $module, 'base_url' => $this->base_url]);
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

        $module = Module::transform($this->module->id);

        $question = call_user_func([$this->module->model_class, 'find'], $id);
        $question->images = null;
        $question->videos = null;
        $question->audios = null;
        $question->tags = $question->tags()->pluck('name')->toArray();

        return view('admin.contents.edit', ['module' => $module, 'content' => $question, 'base_url' => $this->base_url]);
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

        $question = Question::stores($input);

        event(new UserLogEvent(UserLog::ACTION_CREATE . '问答', $question->id, $this->module->model_class));

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

        $question = Question::updates($id, $input);

        event(new UserLogEvent(UserLog::ACTION_UPDATE . '问答', $question->id, $this->module->model_class));

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

    public function top($id)
    {
        $question = Question::find($id);
        $question->top = !$question->top;
        $question->save();
    }

    public function tag($id)
    {
        $tag = request('tag');
        $question = Question::find($id);
        if ($question->tags()->where('name', $tag)->exists()) {
            $question->tags()->where('name', $tag)->delete();
        } else {
            $question->tags()->create([
                'site_id' => $question->site_id,
                'name' => $tag,
                'sort' => strtotime(Carbon::now()),
            ]);
        }
    }

    public function state()
    {
        $input = request()->all();
        Question::state($input);

        $ids = $input['ids'];
        $stateName = Question::getStateName($input['state']);

        //记录日志
        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '问答' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
        }

        //发布页面
        $site = auth()->user()->site;
        if ($input['state'] == Question::STATE_PUBLISHED) {
            foreach ($ids as $id) {
                $this->dispatch(new PublishPage($site, $this->module, $id));
            }
        }
    }

    public function table()
    {
        return Question::table();
    }

    public function categories()
    {
        return Response::json(Category::tree('', 0, $this->module->id));
    }
}
