<?php

namespace App\Http\Controllers;

class TemplateController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $code = file_get_contents(resource_path('views/templates/contents/news.blade.php'));
        return view('admin.templates.index', compact('code'));
    }
}
