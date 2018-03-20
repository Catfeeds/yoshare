<?php

/**
* 商品
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers'], function ($api) {
        $api->get('goods', 'GoodsController@lists');
        $api->get('goods/search', 'GoodsController@search');
        $api->get('goods/info', 'GoodsController@info');
        $api->get('goods/detail', 'GoodsController@detail');
        $api->get('goods/share', 'GoodsController@share');
    });
});