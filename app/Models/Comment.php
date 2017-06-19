<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    const STATE_DELETED = 0;
    const STATE_NORMAL = 1;
    const STATE_PASSED = 9;

    protected $fillable = [
        'site_id',
        'content_id',
        'content_title',
        'content',
        'likes',
        'member_id',
        'ip',
        'username',
        'state',
        'username',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function stateName()
    {
        switch ($this->state) {
            case static::STATE_NORMAL:
                return '未审核';
                break;
            case static::STATE_PASSED:
                return '已审核';
                break;
            case static::STATE_DELETED:
                return '已删除';
                break;
        }
    }

    public function scopeOwns($query)
    {
        $query->where('site_id', Auth::user()->site_id);
    }

    public function scopeFilter($query, $id)
    {
        $query->where(function ($query) use ($id) {
            !empty($id) ? $query->where('content_id', $id) : '';
        });
    }
}