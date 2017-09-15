<?php

/**
* 页面
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('pages/table', 'PageController@table');
    Route::post('pages/state', 'PageController@state');
    Route::get('pages/sort', 'PageController@sort');
    Route::get('pages/comments/{id}','PageController@comments');
    Route::get('pages/{id}/save', 'PageController@save');
    Route::resource('pages', 'PageController');
});
