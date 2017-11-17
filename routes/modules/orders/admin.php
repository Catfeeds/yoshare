<?php

/**
* 订单
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('orders/table', 'OrderController@table');
    Route::post('orders/state', 'OrderController@state');
    Route::get('orders/sort', 'OrderController@sort');
    Route::get('orders/comments/{id}','OrderController@comments');
    Route::post('orders/{id}/save', 'OrderController@save');
    Route::post('orders/{id}/top', 'OrderController@top');
    Route::post('orders/{id}/tag', 'OrderController@tag');
    Route::resource('orders', 'OrderController');
});
