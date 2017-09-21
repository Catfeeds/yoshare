<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteUser extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'site_id',
    ];

    protected $table = 'site_user';

}