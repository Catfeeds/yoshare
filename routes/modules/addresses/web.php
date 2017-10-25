<?php

/**
* 地址
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('address/index.html', 'AddressController@lists');
    Route::get('address/detail-{id}.html', 'AddressController@show');
    Route::resource('address', 'AddressController');
});
