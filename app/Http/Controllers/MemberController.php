<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\DataSource;
use App\Models\Domain;
use App\Models\Member;
use App\Models\Goods;
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


        $members = Member::with('wallet')
            ->filter($filters)
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
                'points' => empty($member->wallet) ? 0 : $member->wallet->points,
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
        $wallet = $member->wallet()->first();

        //菜单栏标记
        $system['mark'] = Domain::MARK_MEMBER;
        return view('themes.' . $domain->theme->name . '.members.index', ['site' => $domain->site, 'member' => $member, 'wallet' => $wallet, 'system' => $system]);

    }

    public function vip(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $type = Member::getMember()->type;
        $system['mark'] = Domain::MARK_MEMBER;
        $system['title'] = 'VIP管理';

        return view('themes.' . $domain->theme->name . '.members.vip', ['type' => $type, 'system' => $system]);
    }

    public function phone(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $member = Member::getMember();

        $system['mark'] = Domain::MARK_MEMBER;
        $system['title'] = empty($member['mobile'])? '绑定手机' : '换绑手机';

        return view('themes.' . $domain->theme->name . '.members.phone', ['site' => $domain->site, 'member' => $member, 'system' => $system]);
    }

    public function verify(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $member = Member::getMember();

        $system['mark'] = Domain::MARK_MEMBER;
        $system['title'] = '修改密码';

        return view('themes.' . $domain->theme->name . '.members.verify', ['site' => $domain->site, 'member' => $member, 'system' => $system]);
    }

    public function showReset(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $member = Member::getMember();

        $system['mark'] = Domain::MARK_MEMBER;
        $system['title'] = '修改密码';

        return view('themes.' . $domain->theme->name . '.members.reset', ['site' => $domain->site, 'member' => $member, 'system' => $system]);
    }

    public function reset()
    {
        $input = Request::all();

        $member = Member::getMember();

        if($input['password'] !== $input['password2']){
            return $this->responseError('前后输入的密码不一致，请重新输入');
        }

        if (bcrypt($input['password']) == $member->password){
            return $this->responseError('请勿使用旧密码重置密码');
        }

        $input['password'] = bcrypt($input['password']);
        $res = $member->update($input);

        if($res){
            return $this->responseSuccess($res);
        }

    }

    public function bindMobile()
    {
        $mobile = Request::get('mobile');
        $captcha = Request::get('captcha');

        try {
            $member = Member::getMember();

            if (!$member) {
                return $this->responseError('您还未登录,请登录后操作', 401);
            }
        } catch (Exception $e) {
            return $this->responseError('您还未登录,请登录后操作', 401);
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

    public function collect()
    {
        $input = Request::all();
        try {
            $member = Member::getMember();

            if (!$member) {
                return $this->responseError('您还未登录,请登录后操作', 401);
            }
        } catch (Exception $e) {
            return $this->responseError('您还未登录,请登录后操作', 401);
        }

        $goods = Goods::find($input['goods_id']);
        $favorite = $goods->favorites()->where('member_id', $member->id)->exists();

        if($favorite){
            return $this->responseError('您已收藏此盘');
        }else{

            $res = $goods->favorites()->create([
                'site_id' => $member->site_id,
                'member_id' => $member->id,
            ]);

            return $this->responseSuccess($res);
        }
    }

    public function collections(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $system['mark'] = Domain::MARK_MEMBER;
        $system['title'] = '我的收藏';

        $favorites = Member::getMember()->favorites()->pluck('refer_id')->toArray();
        $goodses = Goods::whereIn('id', $favorites)->get();

        return view('themes.' . $domain->theme->name . '.members.collection', ['goodses' => $goodses, 'system' => $system]);

    }

    public function collectDel()
    {
        $input = Request::all();
        try {
            $member = Member::getMember();

            if (!$member) {
                return $this->responseError('您还未登录,请登录后操作', 401);
            }
        } catch (Exception $e) {
            return $this->responseError('您还未登录,请登录后操作', 401);
        }

        $goods = Goods::find($input['goods_id']);
        $favorite = $goods->favorites()->where('member_id', $member->id)->exists();

        if($favorite){
            $res = $goods->favorites()->where('member_id', $member->id)->delete();
            return $this->responseSuccess($res);
        }else{
            return $this->responseError('收藏的光盘不存在,请刷新重试');
        }
    }

    public function wallet($member_id)
    {
        return view('admin.wallets.index', compact('member_id'));
    }

}
