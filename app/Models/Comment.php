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
        'refer_id',
        'refer_type',
        'summary',
        'likes',
        'member_id',
        'ip',
        'user_id',
        'state',
    ];

    public function refer()
    {
        return $this->morphTo();
    }

    public function files()
    {
        return $this->morphMany(File::class, 'refer');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
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

    public function scopeFilter($query, $filters)
    {
        $query->where(function ($query) use ($filters) {
            !empty($filters['id']) ? $query->where('refer_id', $filters['id']) : '';
            isset($filters['state']) && $filters['state'] !== '' ? $query->where('state', $filters['state']) : '';
        });
    }
}