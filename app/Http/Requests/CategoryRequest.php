<?php

namespace App\Http\Requests;

class CategoryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=> 'required',
            'template'=> 'required',
            'number' => 'sometimes|required|numeric',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '请输入栏目名称',
            'template.required' => '请输入模板名称',
            'number.required' => '请填写商品信息中的单位',
            'number.numeric' => '单位必须是数值',
        ];
    }
}
