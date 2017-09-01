<?php

namespace App\Http\Controllers;

use App\Models\Site;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $site_id = request('site_id') ?: Site::ID_DEFAULT;
        $site = Site::find($site_id);
        if (empty($site)) {
            return abort(404);
        }

        return view('themes.' . $site->theme->name . '.index', compact('site'));
    }
}
