<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Request;
use Response;
use Schema;

class Module extends Model
{
    const ID_ARTICLE = 1;

    const STATE_DISABLE = 0;
    const STATE_ENABLE = 1;

    const STATES = [
        0 => '已禁用',
        1 => '已启用',
    ];

    protected $fillable = [
        'name',
        'title',
        'model_class',
        'controller_class',
        'view_path',
        'state',
    ];

    public function fields()
    {
        return $this->hasMany(ModuleField::class);
    }

    public function stateName()
    {
        return array_key_exists($this->state, static::STATES) ? static::STATES[$this->state] : '';
    }

    public static function stores($input)
    {
        $input['state'] = self::STATE_ENABLE;

        $module = self::create($input);

        \Session::flash('flash_success', '添加成功');
        return true;
    }

    public static function updates($id, $input)
    {
        $module = self::find($id);

        if ($module == null) {
            \Session::flash('flash_warning', '无此记录');
            return false;
        }
        $module->update($input);

        \Session::flash('flash_success', '修改成功');
        return true;
    }

    public static function table()
    {
        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $ds = new DataSource();
        $modules = static::orderBy('id')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $ds->total = static::count();

        $modules->transform(function ($module) {
            return [
                'id' => $module->id,
                'name' => $module->name,
                'title' => $module->title,
                'model_class' => $module->model_class,
                'controller_class' => $module->controller_class,
                'view_path' => $module->view_path,
                'sort' => $module->sort,
                'state' => $module->state,
                'state_name' => $module->stateName(),
                'created_at' => empty($module->created_at) ? '' : $module->created_at->toDateTimeString(),
                'updated_at' => empty($module->updated_at) ? '' : $module->updated_at->toDateTimeString()
            ];
        });

        $ds->data = $modules;

        return Response::json($ds);
    }

    public static function migrate($id)
    {
        $module = Module::generate($id);

        if (!Schema::hasTable($module->name)) {
            Schema::create($module->name, function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
            });
        }

        foreach ($module->fields as $key => $field) {
            if ($key == 0) {
                continue;
            } else {
                $previous = $module->fields[$key - 1];
            }
            if (Schema::hasColumn($module->name, $field->name)) {
                Schema::table($module->name, function (Blueprint $table) use ($field, $previous) {
                    switch ($field->type) {
                        case ModuleField::TYPE_INTEGER:
                        case ModuleField::TYPE_ENTITY:
                            $table->integer($field->name)->after($previous->name)->change();
                            break;
                        case ModuleField::TYPE_TEXT:
                        case ModuleField::TYPE_IMAGE:
                        case ModuleField::TYPE_AUDIO:
                        case ModuleField::TYPE_VIDEO:
                        case ModuleField::TYPE_IMAGES:
                        case ModuleField::TYPE_AUDIOS:
                        case ModuleField::TYPE_VIDEOS:
                            $table->text($field->name, $field->length)->after($previous->name)->change();
                            break;
                        case ModuleField::TYPE_LONG_TEXT:
                        case ModuleField::TYPE_HTML:
                            $table->longText($field->name)->after($previous->name)->change();
                            break;
                        case ModuleField::TYPE_FLOAT:
                            $table->float($field->name)->after($previous->name)->change();
                            break;
                        case ModuleField::TYPE_DATETIME:
                            $table->datetime($field->name)->nullable()->after($previous->name)->change();
                            break;
                    }
                });
            } else {
                Schema::table($module->name, function (Blueprint $table) use ($field, $previous) {
                    switch ($field->type) {
                        case ModuleField::TYPE_INTEGER:
                        case ModuleField::TYPE_ENTITY:
                            $table->integer($field->name)->after($previous->name)->comment($field->title);
                            break;
                        case  ModuleField::TYPE_TEXT:
                        case ModuleField::TYPE_IMAGE:
                        case ModuleField::TYPE_AUDIO:
                        case ModuleField::TYPE_VIDEO:
                        case ModuleField::TYPE_IMAGES:
                        case ModuleField::TYPE_AUDIOS:
                        case ModuleField::TYPE_VIDEOS:
                            $table->text($field->name, $field->length)->after($previous->name)->comment($field->title);
                            break;
                        case ModuleField::TYPE_LONG_TEXT:
                        case ModuleField::TYPE_HTML:
                            $table->text($field->name)->after($previous->name)->comment($field->title);
                            break;
                        case ModuleField::TYPE_FLOAT:
                            $table->float($field->name)->after($previous->name)->comment($field->title);
                            break;
                        case ModuleField::TYPE_DATETIME:
                            $table->datetime($field->name)->nullable()->after($previous->name)->comment($field->title);
                            break;
                    }
                });
            }
        }
    }

    public static function generate($id)
    {
        $module = Module::find($id);
        $module = [
            'id' => $module->id,
            'name' => $module->name,
            'title' => $module->title,
            'model_class' => $module->model_class,
            'controller_class' => $module->controller_class,
            'view_path' => $module->view_path,
            'fa_icon' => $module->fa_icon,
            'groups' => json_decode($module->groups),
            'columns' => $module->fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'name' => $field->column_name,
                    'title' => $field->column_title,
                    'show' => $field->column_show,
                    'align' => $field->column_align,
                    'width' => $field->column_width,
                    'editable' => $field->column_editable,
                    'formatter' => $field->column_formatter,
                    'index' => $field->column_index,

                ];
            }),
            'editors' => $module->fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'name' => $field->editor_name,
                    'title' => $field->editor_title,
                    'show' => $field->editor_show,
                    'type' => $field->editor_type,
                    'options' => $field->editor_options,
                    'columns' => $field->editor_columns,
                    'rows' => $field->editor_rows,
                    'readonly' => $field->editor_readonly,
                    'group' => $field->editor_group,
                    'index' => $field->editor_index,
                ];
            }),
            'fields' => $module->fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'name' => $field->name,
                    'title' => $field->title,
                    'type' => $field->type,
                    'default' => $field->default,
                    'required' => $field->required,
                    'system' => $field->system,
                    'index' => $field->index,
                    'column' => [
                        'name' => $field->column_name,
                        'title' => $field->column_title,
                        'show' => $field->column_show,
                        'align' => $field->column_align,
                        'width' => $field->column_width,
                        'editable' => $field->column_editable,
                        'formatter' => $field->column_formatter,
                        'index' => $field->column_index,

                    ],
                    'editor' => [
                        'name' => $field->editor_name,
                        'title' => $field->editor_title,
                        'show' => $field->editor_show,
                        'type' => $field->editor_type,
                        'options' => $field->editor_options,
                        'columns' => $field->editor_columns,
                        'rows' => $field->editor_rows,
                        'readonly' => $field->editor_readonly,
                        'group' => $field->editor_group,
                        'index' => $field->editor_index,
                    ]
                ];
            }),
        ];

        $module = json_decode(json_encode($module));

        //编辑器分组
        foreach ($module->groups as $group) {
            //过滤
            $group->editors = array_values(array_filter($module->editors, function ($editor) use ($group) {
                return $editor->show && $editor->group == $group->name;
            }));

            //分组排序
            $group->editors = array_sort($group->editors, function ($editor) {
                return $editor->index;
            });
        }

        //表格列过滤
        $module->columns = array_values(array_filter($module->columns, function ($column) {
            return $column->show;
        }));

        //表格列排序
        $module->columns = array_sort($module->columns, function ($column) {
            return $column->index;
        });

        return $module;
    }
}
