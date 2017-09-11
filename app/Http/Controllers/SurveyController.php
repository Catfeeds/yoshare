<?php

namespace App\Http\Controllers;

use App\Http\Requests\SurveyRequest;
use App\Models\Survey;
use App\Models\SurveyItem;
use App\Models\SurveyTitle;
use Auth;
use Gate;
use Request;

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
        $data['state'] = Survey::STATE_NORMAL;
        $data['site_id'] = Auth::user()->site_id;
        $survey = Survey::create($data);

        $title = $data['item_sub_title'];

        if (count($title) != count(array_unique($title))) {

            \Session::flash('flash_warning', '子选项标题不能一样！！');
            return redirect()->to($this->getRedirectUrl())->withInput();

        }

        //判断有无item_sub_title然后存入数据库 子标题
        if (array_key_exists('item_sub_title', $data)) {
            foreach ($data['item_sub_title'] as $item_sub_title) {
                $data3 = [
                    'title' => $item_sub_title,
                ];
                $survey->titles()->create($data3);
            }
        }

        foreach ($title as $item) {
            $title = SurveyTitle::where('title', $item)->where('survey_id', $survey->id)->first();

            if (is_null($title)) {
                return false;
            }
            $title_id = $title->id;

            //判断有无item_title然后存入数据库 子选项
            if (array_key_exists('item_title', $data)) {
                foreach ($data['item_title'] as $item_title) {
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

        $survey = Survey::with('items')->find($id);

        $sub_title = Survey::with('titles')->find($id);

        return view('admin.surveys.edit', compact('survey', 'sub_title'));
    }

    public function update(SurveyRequest $request, $id)
    {
        $survey = Survey::with('items', 'titles')->find($id);
        $data = $request->all();
        $data['username'] = Auth::user()->name;
        $data['state'] = Survey::STATE_NORMAL;
        $data['site_id'] = Auth::user()->site_id;
        $survey->update($data);

        $title = $data['item_sub_title'];

        if (count($title) != count(array_unique($title))) {
            \Session::flash('flash_warning', '子选项标题不能一样！！');
            return redirect()->to($this->getRedirectUrl())->withInput();
        }

        //删除
        if (array_key_exists('item_id', $data)) {
            $delete_item_ids = SurveyItem::where('survey_id', $id)
                ->whereNotIn('id', $data['item_id'])
                ->pluck('id');
            SurveyItem::destroy($delete_item_ids->toArray());
        }

        //删除子标题
        if (array_key_exists('item_sub_id', $data)) {
            $delete_item_ids = SurveyTitle::where('survey_id', $id)
                ->whereNotIn('id', $data['item_sub_id'])
                ->pluck('id');
            SurveyTitle::destroy($delete_item_ids->toArray());
        }
        //子标题 更新
        if (array_key_exists('item_sub_title', $data)) {
            foreach ($data['item_sub_title'] as $k => $item_sub_title) {
                if ($item_sub_title == '') {
                    continue;
                }

                $data3 = [
                    'title' => $item_sub_title,
                    'survey_id' => $id
                ];
                $item1 = SurveyTitle::where('survey_id', $id)->orderBy('id')->skip($k)->take(1)->first();

                //判断存在就修改，不存在就新增
                if ($item1) {
                    $item1->update($data3);
                } else {
                    SurveyTitle::create($data3);
                }
            }
        }

        //子选项更新
        if (array_key_exists('item_title', $data)) {
            foreach ($data['item_title'] as $k => $item_title) {
                if ($item_title == '') {
                    continue;
                }
                $data2 = [
                    'title' => $item_title,
                    'survey_id' => $id,
                ];
                $item = SurveyItem::where('survey_id', $id)->orderBy('id')->skip($k)->take(1)->first();

                //判断存在就修改，不存在就新增
                if ($item) {
                    $item->update($data2);
                } else {
                    SurveyItem::create($data2);
                }
            }
        }

        return redirect(url('admin/surveys'))->with('item', $item)->with('flash_success', '编辑成功！');
    }

    public function table()
    {
        return Survey::table();
    }

    public function state($state)
    {
        $ids = Request::get('ids');

        switch ($state) {
            case Survey::STATE_DELETED:
                if (Gate::denies('@survey-delete')) {
                    \Session::flash('flash_warning', '无此操作权限');
                    return;
                }
                $state_name = '已删除';
                break;
            default:
                \Session::flash('flash_warning', '操作错误!');
                return;
        }

        foreach ($ids as $id) {
            $survey = Survey::find($id);

            if ($survey == null) {
                \Session::flash('flash_warning', '无此记录!');
                return;
            }

            $survey->state = $state;
            $survey->save();
        }

        \Session::flash('flash_success', $state_name . '成功!');
    }

    public function statistic($id)
    {
        $survey = Survey::find($id);
        return view('admin.surveys.show', compact('survey'));
    }

    public function top($id)
    {
        if (Gate::denies('@survey-top')) {
            \Session::flash('flash_warning', '无此操作权限');
            return;
        }

        $survey = Survey::find($id);
        if ($survey->is_top == 1) {
            $survey->is_top = 0;
            $survey->updated_at = date('Y-m-d H:i:s');
            $survey->save();
            \Session::flash('flash_success', '取消推荐成功');
        } else {
            $survey->is_top = 1;
            $survey->updated_at = date('Y-m-d H:i:s');
            $survey->save();
            \Session::flash('flash_success', '推荐成功');
        }
    }
}
