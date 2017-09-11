<?php

/**
* 页面
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('pages/index.html', 'PageController@lists');
    Route::get('pages/detail-{id}.html', 'PageController@show');
    Route::get('pages/{slug}.html', 'PageController@slug');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('pages/table', 'PageController@table');
    Route::post('pages/state', 'PageController@state');
    Route::get('pages/sort', 'PageController@sort');
    Route::get('pages/{id}/save', 'PageController@save');
    Route::resource('pages', 'PageController');
});
