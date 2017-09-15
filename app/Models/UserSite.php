<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class UserSite extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'site_id',
    ];

    public static function lists()
    {
        $user_id = Auth::user()->id;

        $site_ids = UserSite::where('user_id', $user_id)
                    ->pluck('site_id')
                    ->toArray();

        $sites = Site::whereIn('id', $site_ids)
            ->get();

        return $sites;

    }
}