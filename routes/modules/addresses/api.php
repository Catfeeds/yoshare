<?php

/**
* 地址
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers'], function ($api) {
        $api->get('addresses', 'AddressController@lists');
        $api->get('addresses/search', 'AddressController@search');
        $api->get('addresses/info', 'AddressController@info');
        $api->get('addresses/detail', 'AddressController@detail');
        $api->get('addresses/share', 'AddressController@share');
    });
});