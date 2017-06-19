<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\Controllers', 'middleware' => 'throttle:600'], function ($api) {
        $api->get('apps/info', 'AppController@info');
        $api->get('options', 'OptionController@lists');
        $api->post('files/upload', 'FileController@upload');

        /**
         * 栏目
         */
        $api->get('categories/list', 'CategoryController@lists');
        $api->get('categories/owns', 'CategoryController@owns');
        $api->get('categories/detail', 'CategoryController@detail');
        $api->get('categories/buy', 'CategoryController@buy');

        /**
         * 评论
         */
        $api->get('comments/create', 'CommentController@create');
        $api->get('comments/like', 'CommentController@like');

        /**
         * 内容
         */
        $api->get('contents/list', 'ContentController@lists');
        $api->get('contents/search', 'ContentController@search');
        $api->get('contents/slides', 'ContentController@slides');
        $api->get('contents/info', 'ContentController@info');
        $api->get('contents/about', 'ContentController@about');
        $api->get('contents/detail', 'ContentController@detail');
        $api->get('contents/share', 'ContentController@share');

        /**
         * 收藏
         */
        $api->get('favorites/list', 'FavoriteController@lists');
        $api->get('favorites/create', 'FavoriteController@create');
        $api->get('favorites/destroy', 'FavoriteController@destroy');
        $api->get('favorites/delete', 'FavoriteController@delete');
        $api->get('favorites/exist', 'FavoriteController@exist');

        /**
         * 会员
         */
        $api->get('members/login', 'MemberController@login');
        $api->get('members/exlogin', 'MemberController@exLogin');
        $api->get('members/register', 'MemberController@register');
        $api->get('members/info', 'MemberController@info');
        $api->post('members/avatar', 'MemberController@avatar');
        $api->post('members/info/nick', 'MemberController@nick');
        $api->get('members/token/status', 'MemberController@status');
        $api->get('members/mobile/captcha', 'MemberController@getCaptcha');
        $api->get('members/mobile/bind', 'MemberController@bindMobile');
        $api->get('members/mobile/unbind', 'MemberController@unbindMobile');
        $api->get('members/password/change', 'MemberController@changePassword');
        $api->get('members/password/reset', 'MemberController@resetPassword');
        $api->get('members/sign/in', 'MemberController@signIn');
        $api->get('members/sign/status', 'MemberController@signStatus');

        /**
         * 消息
         */
        $api->get('messages/owns', 'MessageController@owns');

        /**
         * 腾讯云
         */
        $api->get('qcloud/refresh/url', 'QCloudController@refreshUrl');
    });

    $api->group(['namespace' => 'App\Api\Controllers', 'middleware' => 'throttle:600'], function ($api) {
        /**
         * 评论
         */
        $api->get('comments/list', 'CommentController@lists');
    });
});