<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Member;

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

        $mark = 'index';
        return view('themes.' . $domain->theme->name . '.index', ['site' => $domain->site, 'mark' => $mark]);
    }

    public function system(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        if (empty(Member::checkLogin())) {
            return view('auth.login');
        }

        $title = '系统设置';
        $back = '/member';
        $member = Member::getMember();

        return view('themes.' . $domain->theme->name . '.system.index', ['member' => $member, 'title' => $title, 'back' => $back]);
    }

    public function about(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }
        $title = '关于我们';
        $back = '/system';

        return view('themes.' . $domain->theme->name . '.system.about', ['site' => $domain->site, 'title' => $title, 'back' => $back]);
    }

}
