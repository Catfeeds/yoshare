<?php

/**
* 专题
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers', 'middleware' => 'throttle:600'], function ($api) {
        $api->get('features', 'FeatureController@lists');
        $api->get('features/search', 'FeatureController@search');
        $api->get('features/info', 'FeatureController@info');
        $api->get('features/detail', 'FeatureController@detail');
        $api->get('features/share', 'FeatureController@share');
    });
});