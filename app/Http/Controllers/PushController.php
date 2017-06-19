<?php

namespace App\Http\Controllers;

use Gate;
use Request;
use App\Models\PushLog;
use App\DataSource;
use Response;


class PushController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
    }

    public function log()
    {
        if (Gate::denies('@push')) {
            $this->middleware('deny403');
        }

        return view('push.log');
    }

    public function logTable()
    {
        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;
        $state = Request::get('state');

        if (empty($state)) {
            $logs = PushLog::with('user')
                ->owns()
                ->orderBy('id', 'desc')
                ->skip($offset)
                ->limit($limit)
                ->get();

            $total = PushLog::owns()
                ->count();
        } else {
            $logs = PushLog::with('user')
                ->owns()
                ->where('state', $state)
                ->orderBy('id', 'desc')
                ->skip($offset)
                ->limit($limit)
                ->get();

            $total = PushLog::owns()
                ->where('state', $state)
                ->count();
        }

        $logs->transform(function ($log) {
            return [
                'id' => $log->id,
                'content_id'=>$log->content_id,
                'content_type'=>$log->typeName(),
                'content_title' => $log->content_title,
                'send_no' => $log->send_no,
                'msg_id' => $log->msg_id,
                'username' => $log->user->name,
                'state_name' => $log->stateName(),
                'state' => $log->state,
                'created_at' => $log->created_at->toDateTimeString(),
                'updated_at' => $log->updated_at->toDateTimeString(),
            ];
        });
        $ds = New DataSource();
        $ds->total = $total;
        $ds->rows = $logs;

        return Response::json($ds);
    }

    public function received()
    {

    }
}
