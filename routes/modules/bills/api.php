<?php

/**
* 账单表
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers'], function ($api) {
        $api->get('bills', 'BillController@lists');
        $api->get('bills/search', 'BillController@search');
        $api->get('bills/info', 'BillController@info');
        $api->get('bills/detail', 'BillController@detail');
        $api->get('bills/share', 'BillController@share');
    });
});