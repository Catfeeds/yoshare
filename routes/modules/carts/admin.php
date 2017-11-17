<?php

/**
* 购物车
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('carts/table', 'CartController@table');
    Route::post('carts/state', 'CartController@state');
    Route::get('carts/sort', 'CartController@sort');
    Route::get('carts/comments/{id}','CartController@comments');
    Route::post('carts/{id}/save', 'CartController@save');
    Route::post('carts/{id}/top', 'CartController@top');
    Route::post('carts/{id}/tag', 'CartController@tag');
    Route::resource('carts', 'CartController');
});
