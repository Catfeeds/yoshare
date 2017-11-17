<?php

/**
* 物流方式
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('ships/table', 'ShipController@table');
    Route::post('ships/state', 'ShipController@state');
    Route::get('ships/sort', 'ShipController@sort');
    Route::get('ships/comments/{id}','ShipController@comments');
    Route::post('ships/{id}/save', 'ShipController@save');
    Route::post('ships/{id}/top', 'ShipController@top');
    Route::post('ships/{id}/tag', 'ShipController@tag');
    Route::resource('ships', 'ShipController');
});
