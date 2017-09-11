<?php

/**
* 视频
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('videos/index.html', 'VideoController@lists');
    Route::get('videos/detail-{id}.html', 'VideoController@show');
    Route::get('videos/{slug}.html', 'VideoController@slug');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('videos/table', 'VideoController@table');
    Route::post('videos/state', 'VideoController@state');
    Route::get('videos/sort', 'VideoController@sort');
    Route::get('videos/{id}/save', 'VideoController@save');
    Route::resource('videos', 'VideoController');
});
