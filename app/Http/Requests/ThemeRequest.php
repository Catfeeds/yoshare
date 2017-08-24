<?php

namespace App\Http\Requests;

class ThemeRequest extends Request
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
            'name' => 'required|alpha_num',
            'title' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '请输入英文名称',
            'name.alpha_num' => '请输入字母或数字',
            'title.required' => '请输入中文名称',
        ];
    }
}
