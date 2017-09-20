<?php

/**
* 专题
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('features/table', 'FeatureController@table');
    Route::post('features/state', 'FeatureController@state');
    Route::get('features/sort', 'FeatureController@sort');
    Route::get('features/comments/{id}','FeatureController@comments');
    Route::get('features/categories', 'FeatureController@categories');
    Route::get('features/{id}/save', 'FeatureController@save');
    Route::resource('features', 'FeatureController');
});
