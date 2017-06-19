<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    const ID_DEFAULT = 1;

    protected $fillable = [
        'name',
        'company',
        'username',
        'app_key',
        'master_secret',
    ];

    public static function getNames() {
        $sites = Site::all();
        $names = [];
        foreach ($sites as $site) {
            $names[$site->id] = $site->name;
        }
        return $names;
    }
}
