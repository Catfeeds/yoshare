<?php

namespace App\Http\Controllers;

use App\Models\DataSource;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Site;
use App\Models\User;
use App\Models\UserSite;
use Auth;
use DB;
use Gate;
use Request;
use Response;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        if (Gate::denies('@user')) {
            $this->middleware('deny403');
        }

        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::all();
        $sites = Site::all();
        $sitesName = Site::getNames();

        return view('admin.users.create', compact('sites', 'roles', 'sitesName'));
    }

    public function store(UserRequest $request)
    {
        $input = Request::all();

        $input['password'] = bcrypt($input['password']);
        $input['state'] = User::STATE_NORMAL;

        $user = User::create($input);

        //获取user表的id
        $id = $user->id;

        if (array_key_exists('role_id', $input)) {
            foreach ($input['role_id'] as $role_id) {
                RoleUser::create([
                    'role_id' => $role_id,
                    'user_id' => $id,
                ]);
            }
        }

        if (array_key_exists('site_ids', $input)) {
            foreach ($input['site_ids'] as $site_id) {
                UserSite::create([
                    'site_id' => $site_id,
                    'user_id' => $id,
                ]);
            }
        }
        \Session::flash('flash_success', '添加成功');

        return redirect('/admin/users');
    }

    public function destroy($id)
    {
        $users = User::find($id);
        if ($users == null) {
            \Session::flash('flash_warning', '无此记录');
            return;
        }

        $users->state = User::STATE_CANCEL;
        $users->save();

        \Session::flash('flash_success', '注销成功');
    }

    public function edit($id)
    {
        $user = User::find($id);
        if ($user == null) {
            \Session::flash('flash_warning', '无此记录');
            return redirect('/admin/users');
        }

        $roles = Role::all();
        $roleUsers = RoleUser::where('user_id', $id)
            ->pluck('role_id')
            ->toArray();

        $sitesName = Site::getNames();
        $sites = Site::all();

        $userSites = UserSite::where('user_id', $id)
            ->pluck('site_id')
            ->toArray();

        return view('admin.users.edit', compact('user', 'sites', 'roles', 'roleUsers', 'userSites', 'sitesName'));
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);

        if ($user == null) {
            \Session::flash('flash_warning', '无此记录');
            return redirect()->to($this->getRedirectUrl())
                ->withInput($request->input());
        }

        $input = Request::all();
        if (!empty($input['new_password'])) {
            $input['password'] = bcrypt($input['new_password']);
        }

        $user->update($input);

        if (array_key_exists('role_id', $input)) {
            DB::table('role_user')->where('user_id', $id)->delete();

            foreach ($input['role_id'] as $role_id) {
                $data = [
                    'role_id' => $role_id,
                    'user_id' => $id,
                ];
                RoleUser::create($data);
            }
        }

        if (array_key_exists('site_ids', $input)) {
            DB::table('user_sites')->where('user_id', $id)->delete();

            foreach ($input['site_ids'] as $site_id) {
                $data = [
                    'site_id' => $site_id,
                    'user_id' => $id,
                ];
                UserSite::create($data);
            }
        }

        \Session::flash('flash_success', '修改成功!');
        return redirect('/admin/users');
    }

    function category($id){
        $user = User::find($id);

        $category_ids = $user->categories->pluck('id')->toArray();
        return Response::json($category_ids);
    }

    public function tree($id)
    {
        return Response::json(User::getTree($id));
    }

    public function grant($id)
    {
        $user = User::find($id);

        $category_ids = Request::get('category_ids') ? Request::get('category_ids') : [];

        $user->categories()->sync($category_ids);

        \Session::flash('flash_success', '栏目查看更新成功!');
    }

    public function table()
    {
        $users = User::owns()->with('roles')->get();

        $names = Site::getNames();

        $users->transform(function ($user) use ($names) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'password' => $user->password,
                'site_name' => $names[$user->site_id],
                'state_name' => $user->stateName(),
                'role_name' => $user['relations']['roles']->pluck('name'),
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        $ds = new DataSource();
        $ds->data = $users;

        return Response::json($ds);
    }

    public function changePasswordForm()
    {
        return view('admin.auth.passwords.change');
    }

    public function changePassword()
    {
        $user = User::find(Auth::user()->id);

        if ($user == null) {
            return ('<script>alert("无该条记录!");window.location.href="/password/change"</script>;');
        }

        $input = Request::all();

        if (!password_verify($input['old'], $user->password)) {
            return ('<script>alert("旧密码输入错误!");window.location.href="/password/change"</script>;');
        }

        if ($input['new'] != $input['confirm']) {
            return ('<script>alert("两次输入的密码不一致!");window.location.href="/password/change"</script>;');
        }

        $user->password = bcrypt($input['new']);

        $user->save();

        return ('<script>alert("修改成功!");window.location.href="/"</script>;');
    }
}
