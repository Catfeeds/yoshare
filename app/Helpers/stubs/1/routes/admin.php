<?php

/**
* __module_title__
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('__module_path__/table', '__controller__@table');
    Route::post('__module_path__/state', '__controller__@state');
    Route::get('__module_path__/sort', '__controller__@sort');
    Route::get('__module_path__/comments/{id}','__controller__@comments');
    Route::post('__module_path__/{id}/save', '__controller__@save');
    Route::post('__module_path__/{id}/top', '__controller__@top');
    Route::post('__module_path__/{id}/tag', '__controller__@tag');
    Route::resource('__module_path__', '__controller__');
});
