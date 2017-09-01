<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class SmsLog extends Model
{
   
    protected $fillable = [
        'site_id',
        'mobile',
        'message'
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


}
?>