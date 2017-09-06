<?php

/**
* 文章
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('articles/table', 'ArticleController@table');
    Route::post('articles/state', 'ArticleController@state');
    Route::get('articles/sort', 'ArticleController@sort');
    Route::get('articles/comments/{article_id}','ArticleController@comment');
    Route::get('articles/categories', 'ArticleController@categories');
    Route::get('articles/{id}/save', 'ArticleController@save');
    Route::resource('articles', 'ArticleController');
});
