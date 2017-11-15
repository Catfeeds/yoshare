<?php

namespace App\Models;

use Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use Notifiable;
    const ID_ADMIN = 1;

    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;

    const TYPE_NORMAL = 1;
    const TYPE_EXPERT = 2;

    const TYPES = [
        1 => '普通会员',
    ];

    protected $fillable = [
        'username',
        'password',
        'email',
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
        return array_key_exists($this->type, static::TYPES) ? static::TYPES[$this->type] : '';
    }

    public function scopeFilter($query, $filters)
    {
        $query->where(function ($query) use ($filters) {
            !empty($filters['id']) ? $query->where('id', $filters['id']) : '';
            !empty($filters['username']) ? $query->where('username', 'like', '%' . $filters['username'] . '%') : '';
            !empty($filters['mobile']) ? $query->where('mobile', $filters['mobile']) : '';
            $filters['state'] != '' ? $query->where('state', $filters['state']) : '';
            !empty($filters['start_date']) ? $query->where('created_at', '>=', $filters['start_date'])
                ->where('created_at', '<=', $filters['end_date']) : '';
        });
    }

    public function addresses(){
        return $this->hasMany(Address::class);
    }

}
