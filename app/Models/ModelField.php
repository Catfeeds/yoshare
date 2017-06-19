<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelField extends Model
{
    protected $fillable = [
        'model_id',
        'name',
        'alias',
        'type',
        'length',
    ];
}
