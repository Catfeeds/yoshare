<?php

namespace App\Http\Controllers;

use App\Models\IpLog;
use App\Models\UvLog;
use DB;

class AdminController extends BaseController
{
    public function __construct()
    {
    }

    public function login()
    {
        return view('admin.login');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function browsers()
    {
        $logs = UvLog::select(DB::raw('browser as name'), DB::raw('count(*) as value'))
            ->groupBy('browser')
            ->get();

        $logs = $logs->sortByDesc('value');

        return $this->response([
            'browsers' => $logs->pluck('name')->toArray(),
            'data' => array_values($logs->toArray()),
        ]);
    }

    public function areas()
    {
        $logs = DB::select('date_format(created_at, \'%Y-%m-%d\'), province as name, sum(count) as value')
            ->groupBy('browser')
            ->get();

        $logs = $logs->sortByDesc('value');

        return $this->response([
            'browsers' => $logs->pluck('name')->toArray(),
            'data' => array_values($logs->toArray()),
        ]);
    }
}
