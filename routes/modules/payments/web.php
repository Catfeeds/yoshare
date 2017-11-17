<?php

/**
* 支付方式
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('payments/index.html', 'PaymentController@lists');
    Route::get('payments/detail-{id}.html', 'PaymentController@show');
    Route::get('payments/{slug}.html', 'PaymentController@slug');
});
