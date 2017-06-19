<?php

namespace App\Models;

use App\Node;

class Model extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'name',
        'description',
        'table_name',
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
}
