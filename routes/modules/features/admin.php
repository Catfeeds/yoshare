<?php

/**
* 专题
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('features/tree', 'FeatureController@tree');
    Route::get('features/table', 'FeatureController@table');
    Route::get('features/table/{category_id}/{time}', 'FeatureController@columnTable');
    Route::get('features/table/{category_id}', 'FeatureController@columnTable');
    Route::post('features/state', 'FeatureController@state');
    Route::get('features/sort', 'FeatureController@sort');
    Route::get('features/comments/{id}','FeatureController@comments');
    Route::get('features/categories', 'FeatureController@categories');
    Route::get('features/{id}/save', 'FeatureController@save');
    Route::get('features/column', 'FeatureController@column');
    Route::get('features/column/create/{category_id}', 'FeatureController@columnCreate');
    Route::get('features/column/{id}/edit', 'FeatureController@columnEdit');
    Route::resource('features', 'FeatureController');
});
