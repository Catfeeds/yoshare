<?php

/**
* 问答
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('questions/table', 'QuestionController@table');
    Route::post('questions/state', 'QuestionController@state');
    Route::get('questions/sort', 'QuestionController@sort');
    Route::get('questions/comments/{id}','QuestionController@comment');
    Route::get('questions/{id}/save', 'QuestionController@save');
    Route::resource('questions', 'QuestionController');
});
