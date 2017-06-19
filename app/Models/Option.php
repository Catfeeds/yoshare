<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Option extends Model
{
    //评论是否需要审核
    const COMMENT_REQUIRE_PASS = 'COMMENT_REQUIRE_PASS';

    protected $fillable = [
        'code',
        'name',
        'value',
        'site_id',
        ];

    /**
     * 根据编码获取值
     * @param $code
     * @return null
     */
    public static function getValue($code)
    {
        $option = Option::where('code', $code)->first();
        if ($option) {
            return $option->value;
        }
        else{
            return null;
        }
    }

    public function scopeOwns($query)
    {
        $query->where('site_id', Auth::user()->site_id);
    }
}
