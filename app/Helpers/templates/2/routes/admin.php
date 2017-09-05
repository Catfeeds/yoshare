<?php

/**
* __module_title__
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('__module_path__/table', '__controller__@table');
    Route::post('__module_path__/state', '__controller__@state');
    Route::get('__module_path__/sort', '__controller__@sort');
    Route::get('__module_path__/comments/{article_id}','__controller__@comment');
    Route::get('__module_path__/categories', '__controller__@categories');
    Route::get('__module_path__/{id}/save', '__controller__@save');
    Route::resource('__module_path__', '__controller__');
});
