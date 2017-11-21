<?php

namespace App\Http\Controllers;

use App\Models\Domain;

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

    public function set(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }
        $title = '系统设置';
        $back = '/member';

        return view('themes.' . $domain->theme->name . '.set.index', ['site' => $domain->site, 'title' => $title, 'back' => $back]);
    }

    public function cart(Domain $domain)
    {
        if (empty($domain->site)) {
            return abort(501);
        }

        $mark = 'cart';
        return view('themes.' . $domain->theme->name . '.cart.index', ['site' => $domain->site, 'mark' => $mark]);
    }
}
