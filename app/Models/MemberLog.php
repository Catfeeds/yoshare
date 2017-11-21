<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class MemberLog extends Model
{
    const ACTION_LOGIN = '登录系统';
    const ACTION_LOGOUT = '退出系统';
    const ACTION_CREATE = '添加';
    const ACTION_UPDATE = '修改';
    const ACTION_DELETE = '删除';
    const ACTION_STATE = '状态';

    protected $fillable = [
        'site_id',
        'refer_id',
        'refer_type',
        'action',
        'ip',
        'member_id',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function Member()
    {
        return $this->belongsTo(Member::class);
    }

    public function refer()
    {
        return $this->morphTo();
    }

    public function scopeOwns($query)
    {
        $query->where('site_id', Member::getMember()->site_id);
    }

    public function scopeFilter($query, $filters)
    {
        $query->where(function ($query) use ($filters) {
            empty($filters['mobile']) ?: $query->where('mobile', $filters['mobile']);
            empty($filters['start_date']) ?: $query->where('created_at', '>=', $filters['start_date']);
            empty($filters['end_date']) ?: $query->where('created_at', '<=', $filters['end_date']);
            empty($filters['member_id']) ?: $query->whereHas('user', function ($query) use ($filters) {
                $query->where('id', $filters['member_id']);
            });
        });
    }

    public static function record($action, $refer_id = 0, $refer_type = '')
    {
        static::create([
            'site_id' => Member::checkLogin() ? Member::getMember()->site_id : 0,
            'action' => $action,
            'refer_id' => $refer_id,
            'refer_type' => $refer_type,
            'ip' => get_client_ip(),
            'member_id' => Member::checkLogin() ? Member::getMember()->id : 0,
        ]);
    }
}
