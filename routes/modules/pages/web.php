<?php

/**
* 页面
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('pages/index.html', 'PageController@lists');
    Route::get('pages/detail-{id}.html', 'PageController@show');
    Route::get('pages/{slug}.html', 'PageController@slug');
});
