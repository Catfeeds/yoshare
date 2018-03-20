<?php

/**
* 支付方式
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('payments/table', 'PaymentController@table');
    Route::post('payments/state', 'PaymentController@state');
    Route::get('payments/sort', 'PaymentController@sort');
    Route::get('payments/comments/{id}','PaymentController@comments');
    Route::post('payments/{id}/save', 'PaymentController@save');
    Route::post('payments/{id}/top', 'PaymentController@top');
    Route::post('payments/{id}/tag', 'PaymentController@tag');
    Route::resource('payments', 'PaymentController');
});
