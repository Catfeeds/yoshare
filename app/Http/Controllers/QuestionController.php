<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use App\Models\Question;
use App\Models\Category;
use App\Models\Item;
use App\Models\Module;
use App\Models\Site;
use App\Models\UserLog;
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
        $module = Module::where('name', 'Question')->first();
        $this->module = Module::transform($module->id);
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
        $input['site_id'] = Auth::user()->site_id;
        $input['user_id'] = Auth::user()->id;

        $validator = Module::validate($this->module, $input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $question = Question::stores($input);

        //保存图片集、音频集、视频集
        if (!empty($question)) {
            if (isset($input['images'])) {
                Item::sync(Item::TYPE_IMAGE, $question, $input['images']);

            }

            if (isset($input['audios'])) {
                Item::sync(Item::TYPE_AUDIO, $question, $input['audios']);
            }

            if (isset($input['videos'])) {
                Item::sync(Item::TYPE_VIDEO, $question, $input['videos']);
            }
        }

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

        //保存图片集、音频集、视频集
        if (!empty($question)) {
            if (isset($input['images'])) {
                Item::sync(Item::TYPE_IMAGE, $question, $input['images']);

            }

            if (isset($input['audios'])) {
                Item::sync(Item::TYPE_AUDIO, $question, $input['audios']);
            }

            if (isset($input['videos'])) {
                Item::sync(Item::TYPE_VIDEO, $question, $input['videos']);
            }
        }

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

    public function state()
    {
        $input = request()->all();
        Question::state($input);

        $ids = $input['ids'];
        $stateName = Question::getStateName($input['state']);

        foreach ($ids as $id) {
            event(new UserLogEvent('变更' . '问答' . UserLog::ACTION_STATE . ':' . $stateName, $id, $this->module->model_class));
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
