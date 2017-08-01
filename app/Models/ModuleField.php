<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleField extends Model
{
    protected $fillable = [
        'model_id',
        'name',
        'alias',
        'type',
        'length',
    ];
}
