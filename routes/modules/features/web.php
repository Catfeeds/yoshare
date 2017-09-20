<?php

/**
* 专题
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('features/index.html', 'FeatureController@lists');
    Route::get('features/category-{id}.html', 'FeatureController@category');
    Route::get('features/detail-{id}.html', 'FeatureController@show');
    Route::get('features/{slug}.html', 'FeatureController@slug');
});
