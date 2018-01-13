<?php

/**
* 地址
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('members/table', 'MemberController@table');
    Route::post('members/state', 'MemberController@state');
    Route::get('members/sort', 'MemberController@sort');
    Route::get('members/comments/{id}','MemberController@comments');
    Route::post('members/{id}/save', 'MemberController@save');
    Route::post('members/{id}/top', 'MemberController@top');
    Route::post('members/{id}/tag', 'MemberController@tag');
    Route::resource('members', 'MemberController');
});
