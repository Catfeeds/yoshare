<?php

/**
* 问答
*/
Route::group(['middleware' => 'web', 'namespace' => 'Modules\Question\Web'], function () {
    Route::get('questions/index.html', 'QuestionController@lists');
    Route::get('questions/detail-{id}.html', 'QuestionController@show');
    Route::get('questions/{slug}.html', 'QuestionController@slug');
});
