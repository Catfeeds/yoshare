<?php

/**
* 钱包
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers'], function ($api) {
        $api->get('wallets', 'WalletController@lists');
        $api->get('wallets/search', 'WalletController@search');
        $api->get('wallets/info', 'WalletController@info');
        $api->get('wallets/detail', 'WalletController@detail');
        $api->get('wallets/share', 'WalletController@share');
    });
});