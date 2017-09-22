<?php

namespace App\Http\Controllers;

use App\Http\Requests\SurveyRequest;
use App\Models\Survey;
use App\Models\SurveyItem;
use App\Models\SurveyTitle;
use Auth;
use Gate;

class SurveyController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $this->middleware('auth');
        if (Gate::denies('@survey')) {
            $this->middleware('deny403');
        }
        return view('admin.surveys.index');
    }

    public function destroy($id)
    {
        if (Gate::denies('@survey-delete')) {
            \Session::flash('flash_warning', '无此操作权限');
            return;
        }

        $vote = Survey::find($id);
        $vote->state = Survey::STATE_DELETED;
        $vote->save();
        \Session::flash('flash_success', '删除成功');
    }

    public function create()
    {
        if (Gate::denies('@survey-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $default_title = SurveyTitle::DEFAULT_OPTIONS;

        return view('admin.surveys.create', compact('default_title'));
    }


    public function store(SurveyRequest $request)
    {
        $data = $request->all();
        $data['username'] = Auth::user()->name;
        $data['user_id'] = Auth::user()->id;
        $data['state'] = Survey::STATE_NORMAL;
        $data['site_id'] = Auth::user()->site_id;
        $survey = Survey::create($data);

        $title = $data['sub_title']; //子题目

        if (count($title) != count(array_unique($title))) {

            \Session::flash('flash_warning', '子选项标题不能一样！！');
            return redirect()->to($this->getRedirectUrl())->withInput();

        }

        //判断有无item_sub_title然后存入数据库 子标题
        if (array_key_exists('sub_title', $data)) {
            foreach ($data['sub_title'] as $item_sub_title) {
                $data3 = [
                    'title' => $item_sub_title,
                ];
                $survey->titles()->create($data3);
            }
        }

        //存储题目的子选项
        foreach ($title as $k => $item) {
            $title = SurveyTitle::where('title', $item)->where('survey_id', $survey->id)->first();

            if (is_null($title)) {
                return false;
            }
            $title_id = $title->id;

            //判断有无item_title然后存入数据库 子选项
            if (array_key_exists('sub_title_item_' . ($k + 1), $data)) {
                foreach ($data['sub_title_item_' . ($k + 1)] as $item_title) {
                    $data2 = [
                        'title' => $item_title,
                        'survey_title_id' => $title_id
                    ];
                    $survey->items()->create($data2);
                }
            }
        }

        return redirect(url('admin/surveys'))->with('flash_success', '新增成功！');
    }

    public function show($id)
    {
        $site_id = Auth::user()->site_id;

        $survey = Survey::find($id);

        return view("mobile.$site_id.admin.surveys.share", compact('survey'));
    }

    public function edit($id)
    {
        if (Gate::denies('@survey-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $survey = Survey::with('items', 'titles')->find($id);

        $sub_title = Survey::with('titles')->find($id); //子标题
        $sub_item = Survey::with('items')->find($id);  //子选项

        return view('admin.surveys.edit', compact('survey', 'sub_title', 'sub_item'));
    }

    public function update(SurveyRequest $request, $id)
    {
        $survey = Survey::with('items', 'titles')->find($id);
        $data = $request->all();
        $data['username'] = Auth::user()->name;
        $data['state'] = Survey::STATE_NORMAL;
        $data['site_id'] = Auth::user()->site_id;
        $survey->update($data);

        $sub_title_name = $data['sub_title'];

        $sub_title_id = $data['sub_title_id'];

        if (count($sub_title_name) != count(array_unique($sub_title_name))) {
            \Session::flash('flash_warning', '子选项标题不能一样！！');
            return redirect()->to($this->getRedirectUrl())->withInput();
        }

        //删除 题目
        if (array_key_exists('sub_title_id', $data)) {
            $delete_title_ids = SurveyTitle::where('survey_id', $id)
                ->whereNotIn('id', $data['sub_title_id'])
                ->pluck('id');
            SurveyTitle::destroy($delete_title_ids->toArray());
        }

        //删除 题目的选项
        if (array_key_exists('sub_item_id', $data)) {
            $delete_item_ids = SurveyItem::where('survey_id', $id)
                ->whereNotIn('id', $data['sub_item_id'])
                ->pluck('id');
            SurveyItem::destroy($delete_item_ids->toArray());
        }


        //题目 更新
        if (array_key_exists('sub_title', $data)) {
            foreach ($data['sub_title'] as $k => $sub_title) {
                if ($sub_title == '') {
                    continue;
                }

                $data_title = [
                    'title' => $sub_title,
                    'survey_id' => $id
                ];
                $item1 = SurveyTitle::where('survey_id', $id)->orderBy('id')->skip($k)->take(1)->first();

                //判断存在就修改，不存在就新增
                if ($item1) {
                    $item1->update($data_title);
                } else {
                    SurveyTitle::create($data_title);
                }
            }
        }

        //存储题目的子选项
        foreach ($sub_title_id as $key => $item) {
            //判断有无item_title然后存入数据库 子选项
            if (array_key_exists('sub_title_item_' . ($key + 1), $data)) {
                foreach ($data['sub_title_item_' . ($key + 1)] as $k => $sub_title_item) {

                    if ($sub_title_item == '') {
                        continue;
                    }

                    $data_item = [
                        'title' => $sub_title_item,
                        'survey_id' => $id,
                        'survey_title_id' => $item
                    ];
                    $item1 = SurveyItem::where('survey_title_id', $item)
                        ->where('survey_id', $id)
                        ->orderBy('id')->skip($k)->take(1)->first();

                    //判断存在就修改，不存在就新增
                    if ($item1) {
                        $item1->update($data_item);
                    } else {
                        $survey->items()->create($data_item);
                    }
                }
            }
        }

        return redirect(url('admin/surveys'))->with('item', $item)->with('flash_success', '编辑成功！');
    }

    public function table()
    {
        return Survey::table();
    }

    public function state()
    {
        Survey::state(request()->all());
    }

    public function statistic($id)
    {
        $survey = Survey::find($id);
        return view('admin.surveys.show', compact('survey'));
    }

    public function top()
    {
        if (Gate::denies('@survey-top')) {
            \Session::flash('flash_warning', '无此操作权限');
            return;
        }

        $survey = Survey::find(request()->get('id'));
        if ($survey->is_top == Survey::TOP_TRUE) {
            $survey->is_top = Survey::TOP_FALSE;
            $survey->updated_at = date('Y-m-d H:i:s');
            $survey->save();
            \Session::flash('flash_success', '取消推荐成功');
        } else {
            $survey->is_top = Survey::TOP_TRUE;
            $survey->updated_at = date('Y-m-d H:i:s');
            $survey->save();
            \Session::flash('flash_success', '推荐成功');
        }
    }
}
