<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'HomeController@index');
    Route::get('index.html', 'HomeController@index');
    Route::get('/register', 'HomeController@register');
    Route::get('/login', 'HomeController@login');
    Route::get('/phone/login', 'HomeController@phoneLogin');

    Route::get('/member', 'MemberController@show');
    Route::get('/member/phone', 'MemberController@phone');
    Route::get('/member/bind', 'MemberController@bind');
    Route::get('/member/vip', 'MemberController@vip');
    Route::get('/wallet/{type}', 'UserController@wallet');
    Route::get('/wallet/coupon', 'UserController@coupon');
    Route::get('/orders', 'GoodsController@order');
    Route::get('/cart', 'HomeController@cart');


// 网站后端数据统计接口
    Route::get('access/tend/{num}', 'AccessController@tend');
    Route::get('access/progress/{num}', 'AccessController@progress');
    Route::get('access/area/{limit?}', 'AccessController@area');
    Route::get('access/browser', 'AccessController@browser');
});