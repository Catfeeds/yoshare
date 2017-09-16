<?php

namespace App\Http\Controllers;

use App\Models\DataSource;
use App\Models\PushLog;
use App\Models\User;
use Gate;
use Request;
use Response;


class PushController extends Controller
{
    public function log()
    {
        if (Gate::denies('@push')) {
            $this->middleware('deny403');
        }

        $users = User::pluck('name', 'id')
            ->toArray();
        //添加空选项
        array_unshift($users, '');

        return view('admin.logs.push', compact('users'));
    }

    public function logTable()
    {
        $filter = Request::all();

        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $logs = PushLog::with('user')
            ->owns()
            ->filter($filter)
            ->orderBy('id', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $total = PushLog::owns()
            ->filter($filter)
            ->count();

        $logs->transform(function ($log) {
            return [
                'id' => $log->id,
                'content_id' => $log->content_id,
                'content_type' => $log->typeName(),
                'content_title' => $log->content_title,
                'send_no' => $log->send_no,
                'msg_id' => $log->msg_id,
                'username' => empty($log->user) ? '': $log->user->name,
                'state_name' => $log->state_name,
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
}
