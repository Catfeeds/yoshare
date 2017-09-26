<?php

/**
* 专题
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('specials/index.html', 'SpecialController@lists');
    Route::get('specials/category-{id}.html', 'SpecialController@category');
    Route::get('specials/detail-{id}.html', 'SpecialController@show');
    Route::get('specials/{slug}.html', 'SpecialController@slug');
});
