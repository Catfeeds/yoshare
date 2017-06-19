<?php

namespace App\Http\Controllers;

use App\DataSource;
use App\Http\Requests\MemberRequest;
use App\Models\Member;
use Carbon\Carbon;
use Exception;
use Gate;
use Request;
use Response;
use UcClient;

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

        return view('members.index');
    }

    public function create()
    {
        if (Gate::denies('@member-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        return view('members.create');
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
            $ret = UcClient::userRegister($member_name, $password, $nick_name . '@asia-cloud.com', '', '', $ip);

            $member = Member::create([
                'id' => $ret['uid'],
                'name' => $member_name,
                'nick_name' => $nick_name,
                'mobile' => $member_name,
                'avatar_url' => $input['avatar_url'],
                'type' => $input['type'],
                'state' => Member::STATE_ENABLED,
                'points' => 0,
                'ip' => $ip,
            ]);
            $member->token = \JWTAuth::fromUser($member);
            $member->save();

            \Session::flash('flash_success', '添加成功');
            return redirect('/members');
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
            return redirect('/members');
        }

        return view('members.edit', compact('member'));
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
        $new_password = $input['new_password'];
        if (!empty($input['new_password'])) {
            //重置密码
            UcClient::userEdit($member->name, null, $new_password, null, 1);
        }

        $member->update($input);

        \Session::flash('flash_success', '修改成功!');
        return redirect('/members');
    }

    public function message($member_id)
    {
        return view('members.message', compact('member_id'));
    }

    public function table()
    {
        $filters = [
            'id' => Request::has('id') ? intval(Request::get('id')) : 0,
            'nick_name' => Request::has('nick_name') ? trim(Request::get('nick_name')) : '',
            'mobile' => Request::has('mobile') ? trim(Request::get('mobile')) : '',
            'state' => Request::has('state') ? Request::get('state') : '',
            'start_date' => Request::has('start_date') ? trim(Request::get('start_date')) : '',
            'end_date' => Request::has('end_date') ? trim(Request::get('end_date')) : Carbon::now()->toDateTimeString(),
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
                'nick_name' => $member->nick_name,
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
}
