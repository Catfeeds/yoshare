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
            'model_id'=> 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '请输入栏目名称',
            'model_id.required' => '请选择模型',
        ];
    }
}
