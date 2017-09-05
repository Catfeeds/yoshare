<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class SmsLog extends Model
{
    const STATE_SUCCESS = 1;
    const STATE_FAILURE = 2;
    
    protected $fillable = [
        'site_id',
        'mobile',
        'message',
        'state'
    ];
    public function scopeOwns($query)
    {
        $query->where('site_id', Auth::user()->site_id);
    }
   public function site()
    {
        return $this->hasOne('App\Models\Site','id', 'site_id');
    }
    public function scopeFilter($query, $filters)
    {
        $query->where(function ($query) use ($filters) {
            !empty($filters['mobile']) ? $query->where('mobile', $filters['mobile']) : '';
            !empty($filters['start_date']) ? $query->where('created_at', '>=', $filters['start_date'])
                ->where('created_at', '<=', $filters['end_date']) : '';
        });
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


}
?>