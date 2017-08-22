<?php

/**
 * 文章
 */
Route::group(['middleware' => 'web'], function () {
    Route::get('index.html', 'HomeController@index');
    Route::get('articles/list-{category}.html', 'ArticleController@lists');
    Route::get('articles/detail-{article}.html', 'ArticleController@show');
    Route::get('articles/{slug}.html', 'ArticleController@slug');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('articles/table', 'ArticleController@table');
    Route::post('articles/state', 'ArticleController@state');
    Route::get('articles/sort', 'ArticleController@sort');
    Route::get('articles/categories', 'ArticleController@categories');
    Route::resource('articles', 'ArticleController');
});
