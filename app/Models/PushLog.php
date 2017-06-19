<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class PushLog extends Model
{
    const STATE_SUCCESS = 1;
    const STATE_FAILURE = 2;

    const TYPE_NEWS = 1;

    const IOS_PUSH_PRODUCTION = 1;
    const IOS_PUSH_DEVELOPMENT = 2;
    const IOS_PUSH_NONE = 0;

    const IOS_PUSH_OPTIONS = [
        1 => '生产环境',
        2 => '开发环境',
        0 => '不推送',
    ];

    const ANDROID_PUSH_OPTIONS = [
        1 => '推送',
        0 => '不推送',
    ];

    protected $fillable = [
        'content_id',
        'content_type',
        'content_title',
        'state',
        'site_id',
        'url',
        'send_no',
        'msg_id',
        'err_msg',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typeName()
    {
        switch ($this->content_type){
            case static::TYPE_NEWS:
                return '新闻';
                break;
        }
    }

    public function stateName()
    {
        switch ($this->state) {
            case static::STATE_SUCCESS:
                return '成功';
                break;
            case static::STATE_FAILURE:
                return '失败';
                break;
        }
    }

    public function scopeOwns($query)
    {
        $query->where('site_id', Auth::user()->site_id);
    }

}
