<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'site_id',
        'category_id',
        'content_id',
        'title',
        'member_id',
        'member_name',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public static function count($site_id, $member_id)
    {
        return static::where('site_id', $site_id)
            ->where('member_id', $member_id)
            ->count();
    }
}
