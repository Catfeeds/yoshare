<?php

/**
* 账单表
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('bills/table', 'BillController@table');
    Route::post('bills/state', 'BillController@state');
    Route::get('bills/sort', 'BillController@sort');
    Route::get('bills/comments/{id}','BillController@comments');
    Route::post('bills/{id}/save', 'BillController@save');
    Route::post('bills/{id}/top', 'BillController@top');
    Route::post('bills/{id}/tag', 'BillController@tag');
    Route::resource('bills', 'BillController');
});
