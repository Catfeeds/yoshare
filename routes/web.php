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

Route::get('register', 'Member\RegisterController@showRegistrationForm');
Route::post('register', 'Member\RegisterController@register');
Route::get('login', 'Member\LoginController@showLoginForm');
Route::post('login', 'Member\LoginController@login');
Route::get('logout', 'Member\LoginController@logout');
Route::get('/phone/login', 'Member\LoginController@phoneLogin');

Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'HomeController@index');
    Route::get('index.html', 'HomeController@index');

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