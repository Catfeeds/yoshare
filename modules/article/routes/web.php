<?php

/**
* 文章
*/
Route::group(['middleware' => 'web', 'namespace' => 'Modules\Article\Web'], function () {
    Route::get('articles/index.html', 'ArticleController@lists');
    Route::get('articles/category-{id}.html', 'ArticleController@category');
    Route::get('articles/detail-{id}.html', 'ArticleController@show');
    Route::get('articles/{slug}.html', 'ArticleController@slug');
});
