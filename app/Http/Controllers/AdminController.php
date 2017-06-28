<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return redirect('/admin/contents');
    }

    public function login()
    {
        return view('admin.login');
    }
}
