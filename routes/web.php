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

Route::get('/', 'HomeController@index');
Route::get('admin/login', 'HomeController@login');
Route::get('admin/logout', 'Auth\LoginController@logout');
Route::post('admin/login', 'Auth\LoginController@login');
Route::get('auth/captcha', 'Auth\LoginController@captcha');

Route::group(['middleware' => 'auth'], function () {

    Route::get('admin', 'HomeController@admin');

    /**
     * 栏目管理
     */
    Route::get('categories/tree/', 'CategoryController@tree');
    Route::get('categories/table/{category_id}', 'CategoryController@table');
    Route::get('categories/create/{category_id}', 'CategoryController@create');
    Route::get('categories/{id}/save','CategoryController@save');
    Route::resource('/categories', 'CategoryController');
    Route::get('categories/{id}/delete', 'CategoryController@destroy');

    /**
     * 内容管理
     */
    Route::get('contents/categories/', 'ContentController@categories');
    Route::get('contents/table', 'ContentController@table');
    Route::get('contents/create/{category_id}', 'ContentController@create');
    Route::post('contents/state/{state}', 'ContentController@state');
    Route::post('contents/copy', 'ContentController@copy');
    Route::get('contents/sort','ContentController@sort');
    Route::post('contents/push', 'ContentController@push');
    Route::post('contents/tag/{id}','ContentController@tag');
    Route::post('contents/recommend/{id}','ContentController@recommend');
    Route::post('contents/top/{id}','ContentController@top');
    Route::get('contents/comments/{content_id}','ContentController@comment');
    Route::get('contents/{id}/save','ContentController@save');
    Route::resource('contents', 'ContentController');

    /**
     * 评论管理
     */
    Route::get('comments/table', 'CommentController@table');
    Route::post('comments/state/{state}', 'CommentController@state');
    Route::resource('comments', 'CommentController');
    Route::get('comments/pass/{id}', 'CommentController@pass');
    Route::get('comments/{id}/delete', 'CommentController@destroy');

    /**
     * 推送管理
     */
    Route::get('push/log', 'PushController@log');
    Route::get('push/log/table', 'PushController@logTable');
    Route::get('push/received', 'PushController@received');
    Route::resource('push', 'PushController');

    /**
     * 会员管理
     */
    Route::get('members/table', 'MemberController@table');
    Route::get('members/state/{state}', 'MemberController@state');
    Route::resource('members', 'MemberController');
    Route::get('members/messages/{member_id}','MemberController@message');

    /**
     * 消息管理
     */
    Route::get('messages/table', 'MessageController@table');
    Route::post('messages/state/{state}', 'MessageController@state');
    Route::resource('messages', 'MessageController');
    Route::get('messages/pass/{id}', 'MessageController@pass');
    Route::get('messages/{id}/delete', 'MessageController@destroy');

    /**
     * 文件管理
     */
    Route::post('files/upload', 'FileController@upload');
    Route::post('files/delete', 'FileController@delete');

    /**
     * 个人信息
     */
    Route::resource('/profiles', 'ProfileController');

    /**
     * 用户管理
     */
    Route::post('users/category/{id}', 'UserController@category');
    Route::get('users/tree/{id}', 'UserController@tree');
    Route::post('users/grant/{id}', 'UserController@grant');
    Route::get('users/table', 'UserController@table');
    Route::resource('/users', 'UserController');
    Route::get('users/{id}/delete', 'UserController@destroy');

    /**
     * 角色管理
     */
    Route::get('roles/table', 'RoleController@table');
    Route::resource('/roles', 'RoleController');
    Route::get('roles/{id}/delete', 'RoleController@destroy');

    /**
     * 参数设置
     */
    Route::get('options/table', 'OptionController@table');
    Route::resource('/options', 'OptionController');

    /**
     * 数据字典
     */
    Route::get('dictionaries/table', 'DictionaryController@table');
    Route::resource('/dictionaries', 'DictionaryController');
    Route::get('dictionaries/{id}/delete', 'DictionaryController@destroy');

    /**
     * 应用管理
     */
    Route::get('apps/table', 'AppController@table');
    Route::resource('/apps', 'AppController');
    Route::get('apps/{id}/delete', 'AppController@destroy');

    /**
     * 站点管理
     */
    Route::get('sites/table', 'SiteController@table');
    Route::resource('/sites', 'SiteController');
    Route::get('sites/{id}/delete', 'SiteController@destroy');

    /**
     * 模型管理
     */
    Route::get('models/tree/', 'ModelController@tree');
    Route::get('models/table/{category_id}', 'ModelController@table');
    Route::get('models/create/{category_id}', 'ModelController@create');
    Route::get('models/{id}/save','ModelController@save');
    Route::resource('/models', 'ModelController');
    Route::get('models/{id}/delete', 'ModelController@destroy');
});

Route::get('contents/{slug}.html', 'ContentController@slug');
Route::get('categories/{slug}.html', 'CategoryController@slug');
Route::get('categories/list/{category_id}', 'CategoryController@lists');
Route::get('contents/list/{category_id}', 'ContentController@lists');
Route::get('contents/{content}', 'ContentController@show');

