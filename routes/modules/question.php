<?php

/**
* 问答
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('questions/index.html', 'QuestionController@lists');
    Route::get('questions/detail-{id}.html', 'QuestionController@show');
    Route::get('questions/{slug}.html', 'QuestionController@slug');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('questions/table', 'QuestionController@table');
    Route::post('questions/state', 'QuestionController@state');
    Route::get('questions/sort', 'QuestionController@sort');
    Route::get('questions/{id}/save', 'QuestionController@save');
    Route::resource('questions', 'QuestionController');
});
