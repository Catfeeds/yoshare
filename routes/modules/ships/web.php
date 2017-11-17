<?php

/**
* 物流方式
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('ships/index.html', 'ShipController@lists');
    Route::get('ships/detail-{id}.html', 'ShipController@show');
    Route::get('ships/{slug}.html', 'ShipController@slug');
});
