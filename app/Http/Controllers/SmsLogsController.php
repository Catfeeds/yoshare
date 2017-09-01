<?php
namespace App\Http\Controllers;

use Gate;
use Request;
use App\Models\SmsLog;
use App\Models\DataSource;
use Response;


class SmsLogsController extends Controller{
	public  function index(){
		if (Gate::denies('@log')) {
            $this->middleware('deny403');
        }
        return view('admin.logs.sms');
	}

	public function table()
    {
    	$filters = [
            'mobile' => Request::has('mobile') ? trim(Request::get('mobile')) : '',
            'start_date' => Request::has('start_date') ? Request::get('start_date') : '',
            'end_date' => Request::has('end_date') ? Request::get('end_date') : '',
        ];

        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;
        $state = Request::get('state');

        
        $logs = SmsLog::filter($filters)
        	->owns()
            ->orderBy('id', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $total = SmsLog::filter($filters)
        		->owns()
                ->count();
       

        $logs->transform(function ($log) {
            return [
                'id' => $log->id,
                'site'=>$log->site->name,
                'mobile'=>$log->mobile,
                'message' => $log->message,
                'created_at' => $log->created_at->toDateTimeString(),
                'updated_at' => $log->updated_at->toDateTimeString(),
            ];
        });
        $ds = New DataSource();
        $ds->total = $total;
        $ds->rows = $logs;

        return Response::json($ds);
    }
}
?>