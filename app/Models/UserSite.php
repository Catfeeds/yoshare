<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSite extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'site_id',
    ];

}