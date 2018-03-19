<?php

/**
* 钱包
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('wallets/index.html', 'WalletController@lists');
    Route::get('wallets/detail-{id}.html', 'WalletController@show');
    Route::get('wallets/{slug}.html', 'WalletController@slug');
});
