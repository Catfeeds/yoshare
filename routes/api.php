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
        $api->get('categories', 'CategoryController@lists');
        $api->get('categories/detail', 'CategoryController@detail');
        $api->get('categories/info', 'CategoryController@info');

        /**
         * 评论
         */
        $api->get('comments', 'CommentController@lists');
        $api->post('comments/create', 'CommentController@create');
        $api->get('comments/like', 'CommentController@like');

        /**
         * 收藏
         */
        $api->get('favorites', 'FavoriteController@lists');
        $api->post('favorites/create', 'FavoriteController@create');
        $api->get('favorites/destroy', 'FavoriteController@destroy');
        $api->get('favorites/delete', 'FavoriteController@delete');
        $api->get('favorites/exist', 'FavoriteController@exist');

        /**
         * 关注
         */
        $api->get('follows', 'FollowController@lists');
        $api->post('follows/create', 'FollowController@create');
        $api->get('follows/delete', 'FollowController@delete');
        $api->get('follows/exist', 'FollowController@exist');

        /**
         * 投票
         */
        $api->get('votes', 'VoteController@lists');
        $api->post('votes/create', 'VoteController@create');
        $api->get('votes/detail', 'VoteController@detail');

        /**
         * 点赞
         */
        $api->post('likes/create', 'LikeController@create');

        /**
         * 点击数
         */
        $api->post('clicks/create', 'ClickController@create');

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
    });

    $api->group(['namespace' => 'App\Api\Controllers', 'middleware' => 'throttle:60000'], function ($api) {
        $api->get('analyzers/web', 'AnalyzerController@web');
    });
});