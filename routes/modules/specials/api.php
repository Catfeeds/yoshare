<?php

/**
* 专题
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers', 'middleware' => 'throttle:600'], function ($api) {
        $api->get('specials', 'SpecialController@lists');
        $api->get('specials/search', 'SpecialController@search');
        $api->get('specials/info', 'SpecialController@info');
        $api->get('specials/detail', 'SpecialController@detail');
        $api->get('specials/share', 'SpecialController@share');
    });
});