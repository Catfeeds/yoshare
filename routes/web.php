<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'HomeController@index');
    Route::get('index.html', 'HomeController@index');
});

require_once(__DIR__ . '/admin.php');

//动态包含modules下所有文件
$d = dir(__DIR__ . DIRECTORY_SEPARATOR. 'modules');
$dirs = [];
while ($file = $d->read()) {
    if ($file != '.' && $file != '..') {
        if (is_dir(__DIR__ . DIRECTORY_SEPARATOR. 'modules' . DIRECTORY_SEPARATOR . $file)) {//当前为文件
            $dirs[] = $file;
        }
    }
}

foreach ($dirs as $dir) {
    $d = dir(__DIR__ . DIRECTORY_SEPARATOR. 'modules' . DIRECTORY_SEPARATOR . $dir);
    while ($file = $d->read()) {
        if ($file != '.' && $file != '..' && ends_with($file, '.php')) {
            $filename = __DIR__ . DIRECTORY_SEPARATOR. 'modules' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $file;
            if (is_file($filename)) {//当前为文件
                require_once($filename);
            }
        }
    }
}