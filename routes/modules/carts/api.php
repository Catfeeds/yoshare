<?php

/**
* 购物车
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers'], function ($api) {
        $api->get('carts', 'CartController@lists');
        $api->get('carts/search', 'CartController@search');
        $api->get('carts/info', 'CartController@info');
        $api->get('carts/detail', 'CartController@detail');
        $api->get('carts/share', 'CartController@share');
    });
});