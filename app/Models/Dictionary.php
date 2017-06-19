<?php

namespace App\Models;
use Auth;

use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    protected $fillable = [
        'code',
        'name',
        'value',
        'site_id',
    ];

    public function scopeOwns($query)
    {
        $query->where('site_id', Auth::user()->site_id);
    }
}
