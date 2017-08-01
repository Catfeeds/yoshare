<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
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
        'state',
    ];

    public function fields()
    {
        return $this->hasMany(ModuleField::class);
    }

    public static function tree()
    {
        $root = new Node();
        $root->id = 0;
        $root->text = '所有模型';

        $models = Module::all();
        foreach ($models as $model) {
            $node = new Node();
            $node->id = $model->id;
            $node->text = $model->title;
            $root->nodes[] = $node;
        }

        return [$root];
    }

    public static function insert($input)
    {
        $name = $input['name'];

        if (Schema::hasTable($name)) {
            \Session::flash('flash_warning', '此数据库表已存在');
            return false;
        } else {
            Schema::create($name, function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
            });
        }

        \Session::flash('flash_success', '添加成功');
        return true;
    }

    public static function modify($id, $input)
    {
        $model = json_decode(json_encode(config("module.$id")));

        if (!Schema::hasTable($model->name)) {
            Schema::create($model->name, function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
            });
        }

        foreach ($model->fields as $key => $field) {
            if ($key == 0) {
                continue;
            } else {
                $previous = $model->fields[$key - 1];
            }
            if (Schema::hasColumn($model->name, $field->name)) {
                Schema::table($model->name, function (Blueprint $table) use ($field, $previous) {
                    switch ($field->type) {
                        case 'int':
                            $table->integer($field->name)->after($previous->name)->change();
                            break;
                        case 'tinyint':
                            $table->integer($field->name)->after($previous->name)->change();
                            break;
                        case 'varchar':
                            $table->string($field->name, $field->length)->after($previous->name)->change();
                            break;
                        case 'text':
                            $table->text($field->name)->after($previous->name)->change();
                            break;
                        case 'float':
                            $table->float($field->name)->after($previous->name)->change();
                            break;
                        case 'datetime':
                            $table->datetime($field->name)->nullable()->after($previous->name)->change();
                            break;
                    }
                });
            } else {
                Schema::table($model->name, function (Blueprint $table) use ($field, $previous) {
                    switch ($field->type) {
                        case 'int':
                            $table->integer($field->name)->after($previous->name)->comment($field->title);
                            break;
                        case 'tinyint':
                            $table->integer($field->name)->after($previous->name)->comment($field->title);
                            break;
                        case 'varchar':
                            $table->string($field->name, $field->length)->after($previous->name)->comment($field->title);
                            break;
                        case 'text':
                            $table->text($field->name)->after($previous->name)->comment($field->title);
                            break;
                        case 'float':
                            $table->float($field->name)->after($previous->name)->comment($field->title);
                            break;
                        case 'datetime':
                            $table->datetime($field->name)->nullable()->after($previous->name)->comment($field->title);
                            break;
                    }
                });
            }
        }

        \Session::flash('flash_success', '修改成功');
        return true;
    }

    public static function generate($id)
    {
        $model = json_decode(json_encode(config("module.$id")));

        //编辑字段分组
        foreach ($model->groups as $group) {
            $group->fields = array_values(array_filter($model->fields, function ($field) use ($group) {
                return $field->editor->show && $field->editor->group == $group->name;
            }));

            //分组排序
            $group->fields = array_sort($group->fields, function ($field) {
                return $field->editor->index;
            });

            //表格列标题赋值
            foreach ($group->fields as $field) {
                if (!isset($field->editor->readonly)) {
                    $field->editor->readonly = false;
                }
            }
        }

        //表格字段过滤
        $model->grid->fields = array_values(array_filter($model->fields, function ($field) {
            return $field->grid->show;
        }));

        //后台列表排序
        $model->grid->fields = array_sort($model->grid->fields, function ($field) {
            return $field->grid->index;
        });

        //表格列标题赋值
        foreach ($model->fields as $field) {
            if (!isset($field->grid->title)) {
                $field->grid->title = $field->title;
            }
        }

        return $model;
    }
}
