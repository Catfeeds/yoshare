<?php

/**
* 问答
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('questions/index.html', 'QuestionController@lists');
    Route::get('questions/category-{id}.html', 'QuestionController@category');
    Route::get('questions/detail-{id}.html', 'QuestionController@show');
    Route::get('questions/{slug}.html', 'QuestionController@slug');
});
