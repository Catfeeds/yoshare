<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;

    const TYPE_NORMAL = 1;
    const TYPE_TEACHER = 2;

    const ID_ADMIN = 1;

    protected $fillable = [
        'name',
        'password',
        'nick_name',
        'mobile',
        'avatar_url',
        'salt',
        'points',
        'ip',
        'token',
        'type',
        'source',
        'uid',
        'vip_start',
        'vip_end',
        'state',
        'signed_at',
    ]; 

    public function stateName()
    {
        switch ($this->state) {
            case static::STATE_DISABLED:
                return '已禁用';
                break;
            case static::STATE_ENABLED:
                return '已启用';
                break;
        }
    }

    public function typeName()
    {
        switch ($this->type) {
            case static::TYPE_NORMAL:
                return '普通会员';
                break;
            case static::TYPE_TEACHER:
                return '讲师';
                break;
        }
    }

    public function scopeFilter($query, $filters)
    {
        $query->where(function ($query) use ($filters) {
            !empty($filters['id']) ? $query->where('id', $filters['id']) : '';
            !empty($filters['nick_name']) ? $query->where('nick_name', 'like', '%' . $filters['nick_name'] . '%') : '';
            !empty($filters['mobile']) ? $query->where('mobile', $filters['mobile']) : '';
            !empty($filters['start_date']) ? $query->where('created_at', '>=', $filters['start_date'])
                ->where('created_at', '<=', $filters['end_date']) : '';
        });
    }
}
