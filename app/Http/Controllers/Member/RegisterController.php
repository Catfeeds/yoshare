<?php

namespace App\Http\Controllers\Member;

use App\Models\Member;
use App\Models\Wallet;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/member';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:members',
            'email' => 'required|email|max:255|unique:members',
            'password' => 'required|min:6|confirmed',
        ],[
            "min" => ":attribute 至少为 :min 位",
            "confirmed" => "两次输入 :attribute 不一致！",
            "required" => ":attribute 不能为空！",
            "unique" => ":attribute 已存在！",
            "mobile" => ":attribute 手机格式不正确！",
            "email" => ":attribute 邮箱格式不正确！"
        ],[
            'username' => '用户名',
            'email' => '邮箱',
            'password' => '密码',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Member
     */
    protected function create(array $data)
    {
        $member = Member::create([
            'site_id' => Member::SITE_ID,
            'username' => $data['username'],
            'email' => $data['email'],
            'state' => Member::STATE_ENABLED,
            'password' => bcrypt($data['password']),
            'type' => Member::TYPE_ORDINARY,
        ]);

        Wallet::create([
            'member_id' => $member['id'],
            'site_id'   => Member::SITE_ID,
            'deposit'   => Wallet::START_ZERO,
            'balance'   => Wallet::START_MONEY,
            'coupon'    => Wallet::START_ZERO,
            'state'     => Wallet::STATE_NORMAL,
        ]);

        return $member;


    }
}
