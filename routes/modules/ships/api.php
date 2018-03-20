<?php

/**
* 物流方式
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers'], function ($api) {
        $api->get('ships', 'ShipController@lists');
        $api->get('ships/search', 'ShipController@search');
        $api->get('ships/info', 'ShipController@info');
        $api->get('ships/detail', 'ShipController@detail');
        $api->get('ships/share', 'ShipController@share');
    });
});