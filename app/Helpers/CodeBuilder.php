<?php

namespace App\Helpers;

use App\Models\ModuleField;
use App\Models\Permission;

class CodeBuilder
{
    public static function replace($module, $content)
    {
        $content = str_replace('__module_id__', $module->id, $content);
        $content = str_replace('__module_name__', $module->name, $content);
        $content = str_replace('__module_title__', $module->title, $content);
        $content = str_replace('__module_path__', $module->path, $content);
        $content = str_replace('__module_singular__', $module->singular, $content);
        $content = str_replace('__module_plural__', $module->plural, $content);
        $content = str_replace('__table__', $module->table_name, $content);
        $content = str_replace('__model__', $module->model_name, $content);
        $content = str_replace('__controller__', $module->controller_name, $content);
        $content = str_replace('__permission__', $module->singular, $content);

        return $content;
    }

    public static function createModel($module)
    {
        $content = file_get_contents(__DIR__ . '/templates/model.php');

        $content = static::replace($module, $content);

        $fillable = [];
        $dates = [];
        $entities = [];
        foreach ($module->fields as $field) {
            if (in_array($field->name, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }
            if ($field->type == ModuleField::TYPE_DATETIME) {
                $dates[] = '\'' . $field->name . '\'';
            } else if ($field->type == ModuleField::TYPE_ENTITY) {
                $entities[] = '\'' . $field->name . '\'';
            }
            $fillable[] = '\'' . $field->name . '\'';
        }

        $content = str_replace('__fillable__', implode(',', $fillable), $content);
        $content = str_replace('__dates__', implode(',', $dates), $content);
        $content = str_replace('__entities__', implode(',', $entities), $content);

        file_put_contents(base_path('app/Models/' . $module->model_name . '.php'), $content);
    }

    public static function createController($module)
    {
        $content = file_get_contents(__DIR__ . '/templates/controller.php');

        $content = static::replace($module, $content);

        file_put_contents(base_path('app/Http/Controllers/' . $module->controller_name . '.php'), $content);
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
        $content = file_get_contents(__DIR__ . '/templates/route.php');

        $content = static::replace($module, $content);

        file_put_contents(base_path('routes/modules/' . $module->singular . '.php'), $content);

        $content = file_get_contents(base_path('routes/web.php'));

        //判断路由是否已生成
        if (str_contains($content, '/modules/' . $module->singular . '.php')) {
            return;
        }
        //添加路由包含语句
        $content .= PHP_EOL . 'require_once __DIR__ . \'/modules/' . $module->singular . '.php' . '\';';

        file_put_contents(base_path('routes/web.php'), $content);

    }

    public static function appendPermissions($module)
    {
        $module_name = $module->singular;

        //判断权限是否已存在
        if (Permission::where('name', '@' . $module_name)->exists()) {
            return;
        }

        Permission::insert([
            ['name' => '@' . $module_name, 'description' => $module->title, 'sort' => '1'],
            ['name' => '@' . $module_name . '-create', 'description' => $module->title . '-添加', 'sort' => '2'],
            ['name' => '@' . $module_name . '-edit', 'description' => $module->title . '-编辑', 'sort' => '3'],
            ['name' => '@' . $module_name . '-delete', 'description' => $module->title . '-删除', 'sort' => '4'],
            ['name' => '@' . $module_name . '-publish', 'description' => $module->title . '-发布', 'sort' => '5'],
            ['name' => '@' . $module_name . '-cancel', 'description' => $module->title . '-撤回', 'sort' => '6'],
            ['name' => '@' . $module_name . '-sort', 'description' => $module->title . '-排序', 'sort' => '7'],
        ]);
    }
}
