<?php

namespace App\Http\Controllers;

use Session;

class AdminController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return redirect('/admin/articles');
    }

    public function login()
    {
        return view('admin.login');
    }
}
