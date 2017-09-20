<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ID_ADMIN = 1;

    protected $fillable = [
        'name',
        'description',
    ];

    public function perms()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public static function getNames()
    {
        $roles = Role::all();
        $names = [];
        foreach ($roles as $role) {
            $names[$role->id] = $role->name;
        }
        return $names;
    }
}
