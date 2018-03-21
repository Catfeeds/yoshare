<?php

/**
* 账单表
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('bills/index.html', 'BillController@lists');
    Route::get('bills/detail-{id}.html', 'BillController@show');
    Route::get('bills/{slug}.html', 'BillController@slug');
});
