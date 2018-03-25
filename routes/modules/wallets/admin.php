<?php

/**
* 钱包
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('wallets/table', 'WalletController@table');
    Route::post('wallets/state', 'WalletController@state');
    Route::get('wallets/sort', 'WalletController@sort');
    Route::post('wallets/refund/{id}','WalletController@refund');
    Route::post('wallets/{id}/save', 'WalletController@save');
    Route::post('wallets/{id}/top', 'WalletController@top');
    Route::post('wallets/{id}/tag', 'WalletController@tag');
    Route::resource('wallets', 'WalletController');
});
