<?php

/**
* 页面
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers', 'middleware' => 'throttle:600'], function ($api) {
        $api->get('pages', 'PageController@lists');
        $api->get('pages/search', 'PageController@search');
        $api->get('pages/info', 'PageController@info');
        $api->get('pages/detail', 'PageController@detail');
        $api->get('pages/share', 'PageController@share');
    });
});