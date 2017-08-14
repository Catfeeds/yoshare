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

Route::get('/', 'HomeController@index');

Route::group(['prefix' => 'web'], function () {
    Route::get('categories/{category}', 'CategoryController@show');
    Route::get('articles/{slug}.html', 'ArticleController@slug');
    Route::get('articles/list/{category}', 'ArticleController@lists');
    Route::get('articles/{article}', 'ArticleController@show');
});

require_once __DIR__ . '/admin.php';