    /**
    * __module_title__管理
    */
    Route::get('__module_path__/table', '__controller__@table');
    Route::post('__module_path__/state', '__controller__@state');
    Route::get('__module_path__/sort', '__controller__@sort');
    Route::resource('__module_path__', '__controller__');
});
