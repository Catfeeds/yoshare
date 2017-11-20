<?php

/**
* 地址
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('address/index.html', 'AddressController@lists');
    Route::get('address/region', 'AddressController@region');
    Route::resource('address', 'AddressController');
});
