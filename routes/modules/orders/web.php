<?php

/**
* 订单
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('orders/index.html', 'OrderController@lists');
    Route::get('orders/detail-{id}.html', 'OrderController@show');
    Route::get('orders/{slug}.html', 'OrderController@slug');
});
