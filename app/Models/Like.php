<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'site_id',
        'refer_id',
        'refer_type',
        'count',
        'ip',
    ];

    public function refer()
    {
        return $this->morphTo();
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }
}