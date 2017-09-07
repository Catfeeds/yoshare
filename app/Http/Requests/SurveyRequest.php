<?php

namespace App\Http\Requests;

class SurveyRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'item_sub_title.0' => 'required',
            'begin_date' => 'required',
            'end_date' => 'required',
            'end_date' => 'required|after:begin_date',
            'multiple' => 'required',
            'item_title.0' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '请填写调查标题',
            'item_sub_title.0.required' => '请填写子标题1',
            'begin_date.required' => '请选择调查开始日期',
            'end_date.required' => '请选择调查截止日期',
            'end_date.after' => '调查截止日期必须晚于调查开始日期',
            'multiple.required' => '请选择调查类型',
            'item_title.0.required' => '请填写调查选项'
        ];
    }
}