<?php

namespace App\Http\Requests;

class ModelRequest extends Request
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
            'name' => 'required',
            'alias' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '请输入名称',
            'alias.required' => '请输入别名',
        ];
    }
}
