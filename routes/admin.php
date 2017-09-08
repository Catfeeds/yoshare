<?php

Route::group(['prefix' => 'admin'], function () {
    Route::get('login', 'AdminController@login');
    Route::get('logout', 'Auth\LoginController@logout');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('captcha', 'Auth\LoginController@captcha');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('/', 'AdminController@index');

    /**
     * 推送管理
     */
    Route::get('push/log', 'PushController@log');
    Route::get('push/log/table', 'PushController@table');
    Route::resource('push', 'PushController');

    /**
     * SMS日志管理
     */
    Route::get('sms/log', 'SmsController@index');
    Route::get('sms/log/table', 'SmsController@table');

    /**
     * 会员管理
     */
    Route::get('members/table', 'MemberController@table');
    Route::get('members/state/{state}', 'MemberController@state');
    Route::resource('members', 'MemberController');
    Route::get('members/messages/{member_id}', 'MemberController@message');

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
    Route::resource('profiles', 'ProfileController');

    /**
     * 用户管理
     */
    Route::post('users/category/{id}', 'UserController@category');
    Route::get('users/tree/{id}', 'UserController@tree');
    Route::post('users/grant/{id}', 'UserController@grant');
    Route::get('users/table', 'UserController@table');
    Route::resource('users', 'UserController');
    Route::get('users/{id}/delete', 'UserController@destroy');

    /**
     * 角色管理
     */
    Route::get('roles/table', 'RoleController@table');
    Route::resource('roles', 'RoleController');
    Route::get('roles/{id}/delete', 'RoleController@destroy');

    /**
     * 参数设置
     */
    Route::get('options/table', 'OptionController@table');
    Route::resource('options', 'OptionController');

    /**
     * 数据字典
     */
    Route::get('dictionaries/table', 'DictionaryController@table');
    Route::resource('dictionaries', 'DictionaryController');
    Route::get('dictionaries/{id}/delete', 'DictionaryController@destroy');

    /**
     * 应用管理
     */
    Route::get('apps/table', 'AppController@table');
    Route::resource('apps', 'AppController');
    Route::get('apps/{id}/delete', 'AppController@destroy');

    /**
     * 站点管理
     */
    Route::get('sites/table', 'SiteController@table');
    Route::get('sites/{id}/publish', 'SiteController@publish');
    Route::resource('sites', 'SiteController');

    /**
     * 模块管理
     */
    Route::get('modules/table', 'ModuleController@table');
    Route::get('modules/{id}/save', 'ModuleController@save');
    Route::get('modules/{id}/migrate', 'ModuleController@migrate');
    Route::get('modules/{id}/generate', 'ModuleController@generate');
    Route::resource('modules', 'ModuleController');

    /**
     * 字段管理
     */
    Route::get('modules/fields/table/{module_id}', 'ModuleFieldController@table');
    Route::resource('modules/fields', 'ModuleFieldController');

    /**
     * 菜单管理
     */
    Route::get('menus/modules', 'MenuController@modules');
    Route::post('menus/sort', 'MenuController@sort');
    Route::resource('menus', 'MenuController');

    /**
     * 主题管理
     */
    Route::get('themes/tree', 'ThemeController@tree');
    Route::get('themes/modules/{module_id}', 'ThemeController@module');
    Route::get('themes/file', 'ThemeController@readFile');
    Route::post('themes/file', 'ThemeController@createFile');
    Route::put('themes/file', 'ThemeController@writeFile');
    Route::delete('themes/file', 'ThemeController@removeFile');
    Route::resource('themes', 'ThemeController');

    /**
     * 栏目管理
     */
    Route::get('categories/tree/', 'CategoryController@tree');
    Route::get('categories/table/{category_id}', 'CategoryController@table');
    Route::get('categories/create/{category_id}', 'CategoryController@create');
    Route::get('categories/{id}/save', 'CategoryController@save');
    Route::resource('categories', 'CategoryController');
    Route::get('categories/{id}/delete', 'CategoryController@destroy');

    /**
     * 问答管理
     */
    Route::post('questions/reply/{id}', 'QuestionController@reply');

    /**
     * 评论管理
     */
    Route::get('comments/table', 'CommentController@table');
    Route::post('comments/state', 'CommentController@state');
    Route::resource('comments', 'CommentController');
    Route::get('comments/pass/{id}', 'CommentController@pass');
    Route::get('comments/{id}/delete', 'CommentController@destroy');

    /**
     * 问卷管理
     */
    Route::get('survey/items/table/{survey_id}', 'SurveyItemController@table');
    Route::resource('survey/items', 'SurveyItemController');

    Route::get('surveys/table', 'SurveyController@table');
    Route::post('surveys/top/{id}', 'SurveyController@top');
    Route::get('surveys/statistic/{survey_id}', 'SurveyController@statistic');
    Route::post('surveys/state/{state}', 'SurveyController@state');
    Route::resource('surveys', 'SurveyController');

});