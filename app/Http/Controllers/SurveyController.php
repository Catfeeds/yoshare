<?php

namespace App\Http\Controllers;

use App\Http\Requests\SurveyRequest;
use App\Models\Item;
use App\Models\Subject;
use App\Models\Survey;
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

        return view('admin.surveys.create');
    }

    public function store(SurveyRequest $request)
    {
        $data = $request->all();
//        dd($data);
        $data['user_id'] = Auth::user()->id;
        $data['state'] = Survey::STATE_NORMAL;
        $data['site_id'] = Auth::user()->site_id;
        $survey = Survey::create($data);

        $subject = $data['item_subject']; //子题目

        if (count($subject) != count(array_unique($subject))) {
            \Session::flash('flash_warning', '子选项标题不能一样！！');
            return redirect()->to($this->getRedirectUrl())->withInput();
        }

        //判断有无item_subject,存入题目信息
        if (array_key_exists('item_subject', $data)) {
            foreach ($subject as $key => $item_subject) {
                $data1 = [
                    'type' => Item::TYPE_IMAGE,
                    'title' => $item_subject,
                    'summary' => $data['summary_subject'][$key],
                    'url' => $data['item_url_subject'][$key],
                    'sort' => $key
                ];
                $subject = $survey->subjects()->create($data1);

                //存入子选项信息
                if (array_key_exists('item_title' . ($key + 1), $data)) {
                    foreach ($data['item_title' . ($key + 1)] as $k => $item_title) {
                        $data2 = [
                            'type' => Item::TYPE_IMAGE,
                            'title' => $item_title,
                            'summary' => $data['summary' . ($key + 1)][$k],
                            'url' => $data['item_url' . ($key + 1)][$k],
                            'sort' => $k
                        ];
                        $subject->items()->create($data2);
                    }
                }
            }
        }

        return redirect(url('admin/surveys'))->with('flash_success', '新增成功！');
    }

    public function show($id)
    {
        $site_id = Auth::user()->site_id;

        $survey = Survey::with('subjects')->find($id);

        $subject = Subject::with('items')->where('refer_id', $id)->first();;

        return view("mobile.$site_id.admin.surveys.share", compact('survey', 'subject'));
    }

    public function edit(Request $request, $id)
    {
        if (Gate::denies('@survey-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }
        // 一个问卷对应多个题目,一个题目对应多个选项
        $survey = Survey::with('subjects')->find($id);

        $subject = Subject::with('items')->where('refer_id', $id)->get();

        return view('admin.surveys.edit', compact('survey', 'subject'));
    }

    public function update(SurveyRequest $request, $id)
    {
        $survey = Survey::with('subjects')->find($id);

//        $subject = Subject::with('items')->where('refer_id', $id)->first();
        $subject = Subject::with('items')->where('refer_id', $id)->get();

//        dd($subject);
        $data = $request->all();

//        dd($data);

        $data['user_id'] = Auth::user()->id;
        $data['state'] = Survey::STATE_NORMAL;
        $data['site_id'] = Auth::user()->site_id;

        if ($data['link_type'] == Survey::LINK_TYPE_NONE) {
            $data['link'] = '';
        }
        $survey->update($data);

        //删除选项
        foreach ($subject as $k => $item) {
            $item->items()->whereNotIn('id', $data['item_id' . ($k + 1)])->delete();
        }

        $subject = $data['item_subject'];

        if (count($subject) != count(array_unique($subject))) {
            \Session::flash('flash_warning', '子选项标题不能一样！！');
            return redirect()->to($this->getRedirectUrl())->withInput();
        }

        //题目 更新
        if (array_key_exists('item_subject', $data)) {
            foreach ($subject as $key => $item_subject) {
                if ($item_subject == '') {
                    continue;
                }
                $data_subject = [
                    'type' => Item::TYPE_IMAGE,
                    'title' => $item_subject,
                    'summary' => $data['summary_subject'][$key],
                    'url' => $data['item_url_subject'][$key],
                    'sort' => $key
                ];

                if (empty($data['item_id_subject'][$key])) {
                    $te = $survey->subjects()->create($data_subject);
                    //存储题目的子选项
                    if (array_key_exists('item_title' . ($key + 1), $data)) {
                        foreach ($data['item_title' . ($key + 1)] as $k => $item) {
                            if ($item == '') {
                                continue;
                            }
                            $te->items()->create(
                                [
                                    'type' => Item::TYPE_IMAGE,
                                    'title' => $item,
                                    'summary' => $data['summary' . ($key + 1)][$k],
                                    'url' => $data['item_url' . ($key + 1)][$k],
                                    'sort' => $key
                                ]
                            );
                        }
                    }

                } else {
                    $subject = $survey->subjects()->where('id', $data['item_id_subject'][$key])->first();
                    $subject->update($data_subject);
                    //存储题目的子选项
                    if (array_key_exists('item_title' . ($key + 1), $data)) {
                        foreach ($data['item_title' . ($key + 1)] as $k => $item) {
                            if ($item == '') {
                                continue;
                            }
                            $data_item = [
                                'type' => Item::TYPE_IMAGE,
                                'title' => $item,
                                'summary' => $data['summary' . ($key + 1)][$k],
                                'url' => $data['item_url' . ($key + 1)][$k],
                                'sort' => $key
                            ];
                            if (empty($data['item_id' . ($key + 1)][$k])) {
                                $subject = Subject::with('items')->where('refer_id', $id)->get();
                                foreach ($subject as $item) {
                                    $item->items()->create($data_item);
                                }
                            } else {
                                $item2 = Subject::with('items')->where('id', $data['item_id' . ($key + 1)][$k])->first();
                                $item2->update($data_item);
                            }
                        }
                    }
                }

            }
        }
        return redirect(url('admin/surveys'))->with('flash_success', '编辑成功！');
    }

    public function table()
    {
        return Survey::table();
    }

    public function state()
    {
        Survey::state(request()->all());
    }

    public function sort()
    {
        return Survey::sort();
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
            \Session::flash('flash_success', '取消置顶成功');
        } else {
            $survey->is_top = Survey::TOP_TRUE;
            $survey->updated_at = date('Y-m-d H:i:s');
            $survey->save();
            \Session::flash('flash_success', '置顶成功');
        }
    }
}
