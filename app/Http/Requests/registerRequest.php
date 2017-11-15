<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class registerRequest extends FormRequest
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
            'nickname'=>'required|unique:members,name',
            'email' => 'required|email|max:255|unique:members',
            'password' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'nickname.required' => '姓名不能为空',
            'name.unique' => '姓名已存在',
            'email.required' => '邮箱不能为空',
            'email.unique' => '邮箱已注册',
            'password.required' => '密码不能为空',
            'password.min' => '密码长度至少包含6个字符',
        ];
    }
}
