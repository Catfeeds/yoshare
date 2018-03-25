<?php

namespace App\Models;

use Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use Notifiable;
    const ID_ADMIN = 1;
    const SITE_ID = 1;

    const REFER_TYPE = 'App\Models\Member';

    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_REFUNDING = 2;
    const STATE_REFUNDED = 3;

    const TYPE_ORDINARY = 0;
    const TYPE_GOLD = 1;
    const TYPE_PLATINUM = 2;
    const TYPE_DIAMOND = 3;

    const TYPES = [
        0 => '普通用户',
        1 => '黄金会员',
        2 => '铂金会员',
        3 => '钻石会员',
    ];

    const LEVEL = [
        1 => '0.3',
        2 => '0.6',
        3 => '0.9',
    ];

    const SEX = [
        0 => '男',
        1 => '女'
    ];

    const AVATAR = [
        0 => '文艺青年',
        1 => '小仙女',
    ];

    protected $fillable = [
        'site_id',
        'username',
        'password',
        'sex',
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
            case static::STATE_REFUNDING:
                return '退款待审核';
                break;
            case static::STATE_REFUNDED:
                return '已退款';
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
            !empty($filters['type']) ? $query->where('type', $filters['type']) : '';
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

    public function wallet(){
        return $this->hasOne(Wallet::class);
    }

    public function favorites(){
        return $this->hasMany(Favorite::class);
    }

    public static function checkLogin()
    {
        if (!is_null(self::getMember())) {
            return true;
        } else {
            return false;
        }
    }

    public static function getMember()
    {
        return Auth::guard('web')->user();
    }
}
