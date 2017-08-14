<?php

namespace App\Helpers;

class Generator
{
    public static function replace($module, $content)
    {
        $content = str_replace('__module_id__', $module->id, $content);
        $content = str_replace('__module_title__', $module->title, $content);
        $content = str_replace('__module_path__', $module->path, $content);
        $content = str_replace('__table_name__', $module->table_name, $content);
        $content = str_replace('__model__', $module->model_name, $content);
        $content = str_replace('__controller__', $module->controller_name, $content);
        $content = str_replace('__permission__', strtolower($module->name), $content);

        return $content;
    }

    public static function createController($module)
    {
        $content = file_get_contents(__DIR__ . '/templates/controller.php');

        $content = static::replace($module, $content);

        file_put_contents(base_path('app/Http/Controllers/' . $module->controller_name . '.php'), $content);
    }

    public static function createModel($module)
    {
        $content = file_get_contents(__DIR__ . '/templates/model.php');

        $content = static::replace($module, $content);

        file_put_contents(base_path('app/Models/' . $module->model_name . '.php'), $content);
    }

    public static function createViews($module)
    {
        //创建视图目录
        @mkdir(base_path('resources/views/admin/' . $module->path), 0755, true);

        //index.php
        $content = file_get_contents(__DIR__ . '/templates/views/index.blade.php');

        $content = static::replace($module, $content);

        file_put_contents(base_path('resources/views/admin/' . $module->path . '/index.blade.php'), $content);

        //query.php
        $content = file_get_contents(__DIR__ . '/templates/views/query.blade.php');

        $content = static::replace($module, $content);

        file_put_contents(base_path('resources/views/admin/' . $module->path . '/query.blade.php'), $content);

        //script.php
        $content = file_get_contents(__DIR__ . '/templates/views/script.blade.php');

        $content = static::replace($module, $content);

        file_put_contents(base_path('resources/views/admin/' . $module->path . '/script.blade.php'), $content);

        //toolbar.php
        $content = file_get_contents(__DIR__ . '/templates/views/toolbar.blade.php');

        $content = static::replace($module, $content);

        file_put_contents(base_path('resources/views/admin/' . $module->path . '/toolbar.blade.php'), $content);
    }

    public static function appendRoutes($module)
    {
        //移除文件末尾的});
        $routesFile = base_path('routes/web.php');

        $content = file_get_contents($routesFile);
        $content = str_replace('});', '', $content);
        file_put_contents($routesFile, $content);

        //获取路由模板并替换
        $content = file_get_contents(__DIR__ . '/templates/route.php');

        $content = static::replace($module, $content);

        file_put_contents($routesFile, $content, FILE_APPEND);
    }
}
