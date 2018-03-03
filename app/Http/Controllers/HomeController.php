<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Member;
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $system['mark'] = 'index';
        return view('themes.' . $domain->theme->name . '.index', ['site' => $domain->site, 'system' => $system]);
    }

    public function system(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        if (empty(Member::checkLogin())) {
            return view('auth.login');
        }

        $system['title'] = '系统设置';
        $system['back'] = '/member';

        $member = Member::getMember();

        return view('themes.' . $domain->theme->name . '.system.index', ['member' => $member,  'system' => $system]);
    }

    public function about(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $system['title'] = '关于我们';
        $system['back'] = '/system';

        return view('themes.' . $domain->theme->name . '.system.about', ['site' => $domain->site, 'system' => $system]);
    }

    public function checkLogin()
    {
        try {
            $member = Auth::guard('web')->user();

            if (!$member) {
                return $this->responseError('登录已失效,请重新登录', 401);
            }
        } catch (Exception $e) {
            return $this->responseError('登录已失效,请重新登录', 401);
        }
    }

}
