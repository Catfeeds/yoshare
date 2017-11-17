<?php

/**
* 地址
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('address/index.html', 'AddressController@lists');
    Route::resource('address', 'AddressController');
});
