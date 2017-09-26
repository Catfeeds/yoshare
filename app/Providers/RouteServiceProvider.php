<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
            require base_path('routes/admin.php');

            //引用模块路由
            $path = base_path('routes/modules');
            $d = dir($path);
            $dirs = [];
            while ($file = $d->read()) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {//当前为文件
                        $dirs[] = $file;
                    }
                }
            }

            foreach ($dirs as $dir) {
                $d = dir($path . DIRECTORY_SEPARATOR . $dir);
                while ($file = $d->read()) {
                    if ($file != '.' && $file != '..' && ends_with($file, '.php')) {
                        $filename = $path . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $file;
                        if (is_file($filename)) {//当前为文件
                            require $filename;
                        }
                    }
                }
            }
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
