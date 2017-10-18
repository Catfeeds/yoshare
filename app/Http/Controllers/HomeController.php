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
}
