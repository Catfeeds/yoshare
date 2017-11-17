<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use Auth;
use App\Models\DataSource;
use App\Models\Domain;
use App\Models\Member;
use Exception;
use Gate;
use Request;
use Response;

class MemberController extends Controller
{
    public function __construct()
    {
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

    public function update($id, MemberRequest $request)
    {
        $member = Member::find($id);

        if ($member == null) {
            \Session::flash('flash_warning', '无此记录');
            return redirect()->to($this->getRedirectUrl())
                ->withInput($request->input());
        }

        $input = Request::all();
        if (!empty($input['new_password'])) {
            //重置密码
            $new_password = $input['new_password'];
            $input['password'] = md5(md5($new_password) . $member->salt);
        }

        $member->update($input);

        \Session::flash('flash_success', '修改成功!');
        return redirect('/admin/members');
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

        if (empty($this->checkLogin())) {
            return view('auth.login');
        }

        $member = $this->getMember();
        //菜单栏标记
        $mark = Domain::MARK_MEMBER;
        return view('themes.' . $domain->theme->name . '.members.index', ['site' => $domain->site, 'member' => $member, 'mark' => $mark]);

    }

    public function checkLogin()
    {
        if (!is_null($this->getMember())) {
            return true;
        } else {
            return false;
        }
    }
    public function getMember()
    {
        return Auth::guard('web')->user();
    }

    public function vip(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $mark = Domain::MARK_MEMBER;
        $title = 'VIP管理';

        return view('themes.' . $domain->theme->name . '.members.vip', ['title' => $title, 'site' => $domain->site, 'mark' => $mark]);
    }
}
