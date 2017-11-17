<?php

/**
* 支付方式
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers'], function ($api) {
        $api->get('payments', 'PaymentController@lists');
        $api->get('payments/search', 'PaymentController@search');
        $api->get('payments/info', 'PaymentController@info');
        $api->get('payments/detail', 'PaymentController@detail');
        $api->get('payments/share', 'PaymentController@share');
    });
});