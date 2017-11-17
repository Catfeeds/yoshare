<?php

/**
* 订单
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers'], function ($api) {
        $api->get('orders', 'OrderController@lists');
        $api->get('orders/search', 'OrderController@search');
        $api->get('orders/info', 'OrderController@info');
        $api->get('orders/detail', 'OrderController@detail');
        $api->get('orders/share', 'OrderController@share');
    });
});