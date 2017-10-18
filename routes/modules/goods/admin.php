<?php

/**
* 商品
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('goods/table', 'GoodsController@table');
    Route::post('goods/state', 'GoodsController@state');
    Route::get('goods/sort', 'GoodsController@sort');
    Route::get('goods/comments/{id}','GoodsController@comments');
    Route::get('goods/categories', 'GoodsController@categories');
    Route::post('goods/{id}/save', 'GoodsController@save');
    Route::post('goods/{id}/top', 'GoodsController@top');
    Route::post('goods/{id}/tag', 'GoodsController@tag');
    Route::resource('goods', 'GoodsController');
});
