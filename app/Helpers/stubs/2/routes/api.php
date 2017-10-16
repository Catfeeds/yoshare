<?php

/**
* __module_title__
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'Modules\__module_name__\Api'], function ($api) {
        $api->get('__module_path__', '__controller__@lists');
        $api->get('__module_path__/search', '__controller__@search');
        $api->get('__module_path__/info', '__controller__@info');
        $api->get('__module_path__/detail', '__controller__@detail');
        $api->get('__module_path__/share', '__controller__@share');
    });
});