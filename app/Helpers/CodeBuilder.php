<?php

namespace App\Helpers;

use App\Models\ModuleField;
use App\Models\Permission;

class CodeBuilder
{
    private $module;
    private $template_path;

    public function __construct($module)
    {
        $this->module = $module;

        //判断此模块是否有栏目字段
        if ($this->module->fields()->where('name', 'category_id')->count() > 0) {
            $this->template_path = __DIR__ . '/templates/2/';
        } else {
            $this->template_path = __DIR__ . '/templates/1/';
        }
    }

    public function replace($content)
    {
        $content = str_replace('__module_name__', $this->module->name, $content);
        $content = str_replace('__module_title__', $this->module->title, $content);
        $content = str_replace('__module_path__', $this->module->path, $content);
        $content = str_replace('__singular__', $this->module->singular, $content);
        $content = str_replace('__plural__', $this->module->plural, $content);
        $content = str_replace('__table__', $this->module->table_name, $content);
        $content = str_replace('__model__', $this->module->model_name, $content);
        $content = str_replace('__controller__', $this->module->controller_name, $content);
        $content = str_replace('__permission__', $this->module->singular, $content);

        return $content;
    }

    public function createModel()
    {
        $content = file_get_contents($this->template_path . 'model.php');

        $content = static::replace($content);

        $fillable = [];
        $dates = [];
        $entities = [];
        foreach ($this->module->fields()->orderBy('index')->get() as $field) {
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

        file_put_contents(base_path('app/Models/' . $this->module->model_name . '.php'), $content);
    }

    public function createController()
    {
        $content = file_get_contents($this->template_path . 'controller.php');

        $content = static::replace($content);

        file_put_contents(base_path('app/Http/Controllers/' . $this->module->controller_name . '.php'), $content);
    }

    public function createApi()
    {
        $content = file_get_contents($this->template_path . 'api.php');

        $content = static::replace($content);

        file_put_contents(base_path('app/Api/Controllers/' . $this->module->controller_name . '.php'), $content);
    }

    public function createViews()
    {
        //创建视图目录
        @mkdir(base_path('resources/views/admin/' . $this->module->path), 0755, true);

        //index.php
        $content = file_get_contents($this->template_path . 'views/index.blade.php');

        $content = static::replace($content);

        file_put_contents(base_path('resources/views/admin/' . $this->module->path . '/index.blade.php'), $content);

        //query.php
        $content = file_get_contents($this->template_path . 'views/query.blade.php');

        $content = static::replace($content);

        file_put_contents(base_path('resources/views/admin/' . $this->module->path . '/query.blade.php'), $content);

        //script.php
        $content = file_get_contents($this->template_path . 'views/script.blade.php');

        $content = static::replace($content);

        file_put_contents(base_path('resources/views/admin/' . $this->module->path . '/script.blade.php'), $content);

        //toolbar.php
        $content = file_get_contents($this->template_path . 'views/toolbar.blade.php');

        $content = static::replace($content);

        file_put_contents(base_path('resources/views/admin/' . $this->module->path . '/toolbar.blade.php'), $content);
    }

    public function appendRoutes()
    {
        //创建路由目录
        @mkdir(base_path('routes/modules/' . $this->module->path), 0755, true);

        //web.php
        $content = file_get_contents($this->template_path . 'routes/web.php');

        $content = static::replace($content);

        file_put_contents(base_path('routes/modules/' . $this->module->path . '/web.php'), $content);

        //admin.php
        $content = file_get_contents($this->template_path . 'routes/admin.php');

        $content = static::replace($content);

        file_put_contents(base_path('routes/modules/' . $this->module->path . '/admin.php'), $content);

        //api.php
        $content = file_get_contents($this->template_path . 'routes/api.php');

        $content = static::replace($content);

        file_put_contents(base_path('routes/modules/' . $this->module->path . '/api.php'), $content);
    }

    public function appendPermissions()
    {
        $module_name = $this->module->singular;

        //判断权限是否已存在
        if (Permission::where('name', '@' . $module_name)->exists()) {
            return;
        }

        Permission::insert([
            ['name' => '@' . $module_name, 'description' => $this->module->title, 'sort' => '1'],
            ['name' => '@' . $module_name . '-create', 'description' => $this->module->title . '-添加', 'sort' => '2'],
            ['name' => '@' . $module_name . '-edit', 'description' => $this->module->title . '-编辑', 'sort' => '3'],
            ['name' => '@' . $module_name . '-delete', 'description' => $this->module->title . '-删除', 'sort' => '4'],
            ['name' => '@' . $module_name . '-publish', 'description' => $this->module->title . '-发布', 'sort' => '5'],
            ['name' => '@' . $module_name . '-cancel', 'description' => $this->module->title . '-撤回', 'sort' => '6'],
            ['name' => '@' . $module_name . '-sort', 'description' => $this->module->title . '-排序', 'sort' => '7'],
        ]);
    }
}
