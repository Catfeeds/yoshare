<?php

/**
* 问答
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('questions/table', 'QuestionController@table');
    Route::post('questions/state', 'QuestionController@state');
    Route::get('questions/sort', 'QuestionController@sort');
    Route::get('questions/comments/{id}','QuestionController@comments');
    Route::get('questions/categories', 'QuestionController@categories');
    Route::get('questions/{id}/save', 'QuestionController@save');
    Route::post('questions/reply/{id}', 'QuestionController@reply');
    Route::resource('questions', 'QuestionController');
});
