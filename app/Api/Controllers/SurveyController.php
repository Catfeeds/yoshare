<?php

namespace App\Api\Controllers;

use App\Models\Subject;
use App\Models\Survey;
use App\Models\SurveyData;
use Carbon\Carbon;
use Exception;
use Request;

class SurveyController extends BaseController
{
    //TODO
    public function transform($survey, $subjects)
    {
        foreach ($subjects as $k => $subject) {
            $amount = $subject->items->sum('count');

            return [
                'id' => $survey->id,
                'type' => $survey->type,
                'title' => $survey->title,
                'link' => $survey->link,
                'description' => $survey->description,
                'multiple' => !empty($survey->multiple) ? $survey->multiple : 0,
                'image_url' => get_image_url($survey->image_url),
                'amount' => !empty($survey->amount) ? $survey->amount : 0,
                'top' => !empty($survey->top) ? $survey->top : 0,
                'begin_date' => $survey->begin_date,
                'end_date' => $survey->end_date,
                'state' => $survey->state,

                'items' => $subject->items->transform(function ($item) use ($amount) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'url' => get_image_url($item->url),
                        'summary' => $item->summary,
                        'count' => $item->count,
                        'percent' => $amount ? round(($item->count / $amount) * 100) . '%' : 0,
                        'sort' => $item->sort,
                    ];
                }),
            ];
        }
    }

    /**
     * @SWG\Get(
     *   path="/surveys",
     *   summary="获取问卷列表",
     *   tags={"/surveys 问卷"},
     *   @SWG\Parameter(name="site_id", in="query", required=true, description="站点ID", type="string"),
     *   @SWG\Parameter(name="page_size", in="query", required=true, description="分页大小", type="integer"),
     *   @SWG\Parameter(name="page", in="query", required=true, description="分页序号", type="integer"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到路由"
     *   )
     * )
     */
    public function lists()
    {
        $site_id = Request::get('site_id') ?: 1;
        $page_size = Request::get('page_size') ? min(100, Request::get('page_size')) : 20;
        $page = Request::get('page') ? Request::get('page') : 1;


        $surveys = Survey::with('subjects')
            ->where('site_id', $site_id)
            ->where('state', Survey::STATE_PUBLISHED)
            ->where('end_date', '>=', Carbon::now())
            ->orderBy('sort', 'desc')
            ->skip(($page - 1) * $page_size)
            ->limit($page_size)
            ->get();

        $surveys->transform(function ($survey) {

            return $this->transform($survey, $survey->subjects);
        });


        return $this->response([
            'status_code' => 200,
            'message' => 'success',
            'data' => $surveys,
        ]);
    }

    /**
     * @SWG\Post(
     *   path="/surveys/create",
     *   summary="提交问卷",
     *   tags={"/surveys 问卷"},
     *   @SWG\Parameter(name="survey_id", in="query", required=true, description="调查ID", type="string"),
     *   @SWG\Parameter(name="item_ids", in="query", required=true, description="选项ID", type="array", items={"type": "integer"}),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="提交问卷成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   )
     * )
     */
    public function create()
    {
        $survey_id = Request::get('survey_id');
        $item_ids = Request::get('item_ids');

        //判断会员是否存在
        try {
            $member = \JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        //判断是否存在
        $survey = Survey::find($survey_id);

        if (empty($survey)) {
            return $this->responseError('此问卷ID不存在');
        }

        if ($survey->end_date <= Carbon::now()) {
            return $this->responseError('此问卷已经结束,不能提交！');
        }

        //判断是否有选项
        if (empty($item_ids)) {
            return $this->responseError('请选择选项');
        }

        //判断是否已有投票记录
        $data = SurveyData::where('survey_id', $survey_id)
            ->where('member_id', $member->id)
            ->first();

        if (!empty($data)) {
            return $this->responseError('您已经参与过调查！');
        }

        //增加问卷数据记录

        $data = new SurveyData();
        $data->survey_id = $survey_id;
        $data->survey_item_ids = $item_ids;
        $data->avatar_url = $member->avatar_url;
        $data->member_id = $member->id;
        $data->ip = Request::getClientIp();
        $data->save();


        //增加选项调查数
//        SurveyItem::whereIn('id', explode(',', $item_ids))->increment('amount');

        $subjects = $survey->subjects;
        foreach ($subjects as $subject) {
            $subject->items()->whereIn('id', explode(',', $item_ids))->get()->each(function ($item) {
                $item->count += 1;
                $item->save();
            });
        }

        //增加调查数
        $survey->increment('amount');

        return $this->responseSuccess();
    }

    /**
     * @SWG\Get(
     *   path="/surveys/detail",
     *   summary="获取问卷详情页",
     *   tags={"/surveys 问卷"},
     *   @SWG\Parameter(name="survey_id", in="query", required=true, description="问卷ID", type="string"),
     *   @SWG\Parameter(name="site_id", in="query", required=true, description="站点ID", type="string"),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到路由"
     *   )
     * )
     */
    public function detail()
    {
        $site_id = Request::get('site_id') ?: 1;
        $id = Request::get('survey_id');

        $survey = Survey::find($id);

        $amount = $survey->items->sum('amount');

        $sub_title_total = $survey->items->count('title');

        $sub_title_num = $sub_title_total / Subject::OPTIONS_NUM;

        if (empty($survey)) {
            return $this->responseError('此问卷ID不存在');
        }

        if (Request::has('token')) {
            try {
                $member = \JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
                return view('mobile.401');
            }

            return view("mobile.$site_id.surveys.detail",
                compact('survey', 'member', 'amount', 'sub_title_num', 'survey_item_ids_num'));
        } else {
            return view("mobile.$site_id.votes.share", compact('survey', 'amount', 'sub_title_num', 'survey_item_ids_num'));
        }
    }
}