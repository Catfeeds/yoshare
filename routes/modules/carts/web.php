<?php

/**
* 购物车
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('carts/index.html', 'CartController@lists');
    Route::get('carts/detail-{id}.html', 'CartController@show');
    Route::get('carts/{slug}.html', 'CartController@slug');
});
