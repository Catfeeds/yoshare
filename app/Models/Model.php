<?php

namespace App\Models;

use App\Node;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class Model extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'name',
        'alias',
    ];

    public function fields()
    {
        return $this->hasMany(ModelField::class);
    }

    public static function tree()
    {
        $root = new Node();
        $root->id = 0;
        $root->text = '所有模型';

        $node = new Node();
        $node->id = 1;
        $node->text = '新闻';
        $root->nodes[] = $node;

        $node = new Node();
        $node->id = 2;
        $node->text = '视频';
        $root->nodes[] = $node;

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
        $model = json_decode(json_encode(config('site.model.1')));

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
                            $table->datetime($field->name)->after($previous->name)->change();
                            break;
                    }
                });
            } else {
                Schema::table($model->name, function (Blueprint $table) use ($field, $previous) {
                    switch ($field->type) {
                        case 'int':
                            $table->integer($field->name)->after($previous->name);
                            break;
                        case 'tinyint':
                            $table->integer($field->name)->after($previous->name);
                            break;
                        case 'varchar':
                            $table->string($field->name, $field->length)->after($previous->name);
                            break;
                        case 'text':
                            $table->text($field->name)->after($previous->name);
                            break;
                        case 'float':
                            $table->float($field->name)->after($previous->name);
                            break;
                        case 'datetime':
                            $table->datetime($field->name)->after($previous->name);
                            break;
                    }
                });
            }
        }

        \Session::flash('flash_success', '修改成功');
        return true;
    }
}
