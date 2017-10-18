<?php

/**
* 商品
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('goods/index.html', 'GoodsController@lists');
    Route::get('goods/category-{id}.html', 'GoodsController@category');
    Route::get('goods/detail-{id}.html', 'GoodsController@show');
    Route::get('goods/{slug}.html', 'GoodsController@slug');
});
