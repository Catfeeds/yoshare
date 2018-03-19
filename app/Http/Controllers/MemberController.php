<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\DataSource;
use App\Models\Domain;
use App\Models\Member;
use Exception;
use Gate;
use Request;
use Response;
use Cache;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->member = Member::getMember();
    }

    public function index()
    {
        if (Gate::denies('@member')) {
            $this->middleware('deny403');
        }

        return view('admin.members.index');
    }

    public function create()
    {
        if (Gate::denies('@member-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        return view('admin.members.create');
    }

    public function store(MemberRequest $request)
    {
        $input = Request::all();
        $nick_name = $input['nick_name'];
        $member_name = $input['name'];
        $password = $input['password'];
        $ip = Request::getClientIp();

        $member = Member::where('name', $member_name)->first();
        if ($member) {
            \Session::flash('flash_error', '用户名已经存在');
            return redirect()->back()->withInput();
        }

        try {
            $salt = str_rand();

            $member = Member::create([
                'name' => $member_name,
                'password' => md5(md5($password) . $salt),
                'nick_name' => $nick_name,
                'mobile' => $input['mobile'],
                'avatar_url' => $input['avatar_url'],
                'type' => $input['type'],
                'state' => Member::STATE_ENABLED,
                'points' => 0,
                'ip' => $ip,
            ]);
            $member->token = \JWTAuth::fromUser($member);
            $member->save();

            \Session::flash('flash_success', '添加成功');
            return redirect('/admin/members');
        } catch (Exception $e) {
            return false;
        }

    }

    public function edit($id)
    {
        if (Gate::denies('@member-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $member = Member::find($id);

        if ($member == null) {
            \Session::flash('flash_warning', '无此记录');
            return redirect('/admin/members');
        }

        return view('admin.members.edit', compact('member'));
    }

    public function update($id)
    {
        $member = Member::find($id);

        $input = Request::all();

        $member->avatar_url = $input['avatar_url'];
        $member->username   = $input['username'];
        $member->sex        = $input['sex'];
        $member->email      = $input['email'];

        $member->save();

        return redirect('/admin/members');
    }

    public function save($id)
    {
        $member = Member::find($id);

        $input = Request::all();

        $member->avatar_url = $input['avatar_url'];
        $member->username   = $input['username'];
        $member->sex        = $input['sex'];
        $member->email      = $input['email'];

        $member->save();

        return redirect('/member');
    }

    public function message($member_id)
    {
        return view('admin.members.message', compact('member_id'));
    }

    public function table()
    {
        $filters = [
            'id' => Request::has('id') ? intval(Request::get('id')) : 0,
            'username' => Request::has('username') ? trim(Request::get('username')) : '',
            'mobile' => Request::has('mobile') ? trim(Request::get('mobile')) : '',
            'state' => Request::has('state') ? Request::get('state') : '',
        ];

        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;


        $members = Member::filter($filters)
            ->orderBy('id', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $total = Member::filter($filters)
            ->count();


        $members->transform(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'username' => $member->username,
                'mobile' => $member->mobile,
                'avatar_url' => $member->avatar_url,
                'points' => $member->points,
                'ip' => $member->ip,
                'type_name' => $member->typeName(),
                'state_name' => $member->stateName(),
                'signed_at' => $member->signed_at,
                'created_at' => empty($member->created_at) ? '' : $member->created_at->toDateTimeString(),
                'updated_at' => empty($member->updated_at) ? '' : $member->updated_at->toDateTimeString(),
            ];
        });
        $ds = New DataSource();
        $ds->total = $total;
        $ds->rows = $members;

        return Response::json($ds);
    }

    public function state($id)
    {
        $member = Member::find($id);
        if ($member->state == Member::STATE_ENABLED) {
            $member->state = Member::STATE_DISABLED;
            $member->save();
            \Session::flash('flash_success', '禁用成功');
        } else {
            $member->state = Member::STATE_ENABLED;
            $member->save();
            \Session::flash('flash_success', '启用成功');
        }
    }

    public function show(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        if (empty(Member::checkLogin())) {
            return view('auth.login');
        }

        $member = Member::getMember();
        $wallet = $member->wallet();
        dd($wallet);
        //菜单栏标记
        $system['mark'] = Domain::MARK_MEMBER;
        return view('themes.' . $domain->theme->name . '.members.index', ['site' => $domain->site, 'member' => $member, 'wallet' => $wallet, 'system' => $system]);

    }

    public function vip(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $system['mark'] = Domain::MARK_MEMBER;
        $system['title'] = 'VIP管理';

        return view('themes.' . $domain->theme->name . '.members.vip', ['site' => $domain->site, 'system' => $system]);
    }

    public function phone(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $system['mark'] = Domain::MARK_MEMBER;
        $system['title'] = '绑定我的手机';

        return view('themes.' . $domain->theme->name . '.members.phone', ['site' => $domain->site, 'system' => $system]);
    }

    public function bindMobile()
    {
        $mobile = Request::get('mobile');
        $captcha = Request::get('captcha');

        try {
            $member = Member::getMember();

            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        try {
            if (!preg_match("/1[34578]{1}\d{9}$/", $mobile)) {
                throw new Exception('请输入正确的手机号', -1);
            }

            //比较验证码
            $key = 'captcha_' . $mobile;
            if (Cache::get($key) != $captcha) {
                throw new Exception('手机验证码错误', -1);
            }

            $count = Member::where('mobile', $mobile)->count();
            if ($count > 0) {
                throw new Exception('此手机号已经被其他账号绑定', -1);
            }

            $member->mobile = $mobile;
            $member->save();

            //移除验证码
            Cache::forget($key);

            return $this->responseSuccess($member);
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function detail(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $system['mark'] = Domain::MARK_MEMBER;
        $system['title'] = '个人信息页';
        $system['back'] = '/system';

        $member = Member::getMember();
        $member->sexOptions = Member::SEX;
        $member->avatarOptions = Member::AVATAR;

        return view('themes.' . $domain->theme->name . '.system.member', ['member' => $member, 'system' => $system]);
    }

}
