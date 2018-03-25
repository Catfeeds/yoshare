<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Goods;
use App\Models\Member;
use App\Models\Tag;
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

        $recommends = Tag::where('name', Tag::RECOMMEND)
            ->where('refer_type', Goods::TAG_GOODS)
            ->pluck('refer_id');

        $hots = Tag::where('name', Tag::HOT)
            ->where('refer_type', Goods::TAG_GOODS)
            ->pluck('refer_id');

        $system['mark'] = 'index';
        return view('themes.' . $domain->theme->name . '.index', ['site' => $domain->site, 'system' => $system, 'recommends' => $recommends, 'hots' => $hots]);
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

    public function help(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $system['title'] = '帮助与反馈';
        $system['back'] = '/system';

        return view('themes.' . $domain->theme->name . '.system.help', ['site' => $domain->site, 'system' => $system]);
    }

    public function checkLogin()
    {
        try {
            $member = Auth::guard('web')->user();

            if (!$member) {
                return $this->responseError('您还未登录,请登录后操作', 401);
            }
        } catch (Exception $e) {
            return $this->responseError('您还未登录,请登录后操作', 401);
        }
    }

}
