<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return view('index');
    }

    public function login()
    {
        return view('login');
    }

    public function admin()
    {
        return redirect('/contents');
    }

}
