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
// Password Reset Routes...
Route::get('password/reset', 'Member\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Member\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Member\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Member\ResetPasswordController@reset');

Route::get('/phone/login', 'Member\LoginController@phoneLogin');

Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'HomeController@index');
    Route::get('index.html', 'HomeController@index');

    Route::get('/set', 'HomeController@set');
    //会员管理
    Route::get('/member', 'MemberController@show');
    Route::get('/member/phone', 'MemberController@phone');
    Route::get('/member/bind', 'MemberController@bind');
    Route::get('/member/vip', 'MemberController@vip');
    //会员地址
    Route::get('address/index.html', 'AddressController@lists');
    Route::get('address/region', 'AddressController@region');
    Route::get('address/{id}/delete', 'AddressController@destroy');
    Route::resource('address', 'AddressController');
    //会员钱包
    Route::get('/wallet/{type}', 'UserController@wallet');
    Route::get('/wallet/coupon', 'UserController@coupon');

    Route::get('/orders', 'GoodsController@order');
    Route::get('/cart', 'HomeController@cart');

});