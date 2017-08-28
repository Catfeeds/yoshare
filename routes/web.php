<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'HomeController@index');
    Route::get('index.html', 'HomeController@index');
});

require_once __DIR__ . '/admin.php';
require_once __DIR__ . '/modules/article.php';
require_once __DIR__ . '/modules/page.php';
require_once __DIR__ . '/modules/video.php';
require_once __DIR__ . '/modules/question.php';