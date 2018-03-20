<?php

/**
* 地址
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('addresses/table', 'AddressController@table');
    Route::post('addresses/state', 'AddressController@state');
    Route::get('addresses/sort', 'AddressController@sort');
    Route::get('addresses/comments/{id}','AddressController@comments');
    Route::post('addresses/{id}/save', 'AddressController@save');
    Route::post('addresses/{id}/top', 'AddressController@top');
    Route::post('addresses/{id}/tag', 'AddressController@tag');
    Route::resource('addresses', 'AddressController');
});
