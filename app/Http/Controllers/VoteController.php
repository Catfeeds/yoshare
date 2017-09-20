<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteRequest;
use App\Models\DataSource;
use App\Models\Item;
use App\Models\Vote;
use Auth;
use Gate;
use Request;
use Response;


class VoteController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        if (Gate::denies('@vote')) {
            $this->middleware('deny403');
        }

        return view('admin.votes.index');
    }

    public function create()
    {
        if (Gate::denies('@vote-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        return view('admin.votes.create');
    }

    public function store(VoteRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['state'] = Vote::STATE_NORMAL;
        $data['site_id'] = Auth::user()->site_id;
        $vote = Vote::create($data);

        foreach ($data['item_title'] as $k => $item_title) {
            $data2 = [
                'type' => Item::TYPE_IMAGE,
                'title' => $item_title,
                'url' => $data['item_url'][$k],
                'summary' => $data['summaries'][$k],
                'sort' => $k,
            ];
            $vote->items()->create($data2);
        }

        return redirect('/admin/votes')->with('flash_success', '新增成功！');
    }

    public function show($id)
    {
        $vote = Vote::find($id);
        $site = $vote->site;
        $theme = $site->mobile_theme->name;

        return view("themes.$theme.votes.share", compact('vote', 'site'));
    }

    public function edit($id)
    {
        if (Gate::denies('@vote-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $vote = Vote::with('items')->find($id);
        return view('admin.votes.edit')->with('vote', $vote);
    }

    public function update(VoteRequest $request, $id)
    {
        $vote = Vote::with('items')->find($id);
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['state'] = Vote::STATE_NORMAL;
        $data['site_id'] = Auth::user()->site_id;

        if ($data['link_type'] == Vote::LINK_TYPE_NONE) {
            $data['link'] = '';
        }

        $vote->update($data);

        $vote->items()->whereNotIn('id', $data['item_id'])->delete();

        foreach ($data['item_title'] as $k => $item_title) {
            if (!empty(trim($item_title)) || !empty(trim($data['item_url'][$k]))) {
                $data2 = [
                    'type' => Item::TYPE_IMAGE,
                    'title' => $item_title,
                    'url' => $data['item_url'][$k],
                    'summary' => $data['summaries'][$k],
                    'sort' => $k,
                ];
                $item = $vote->items()->orderBy('id')->skip($k)->take(1)->first();

                //判断存在就修改，不存在就新增
                if ($item) {
                    $item->update($data2);
                } else {
                    $vote->items()->create($data2);
                }
            }
        }

        return redirect('/admin/votes')->with('item', $item)->with('flash_success', '编辑成功！');
    }

    public function table()
    {
        $filters = Request::all();
        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $votes = Vote::owns()
            ->filter($filters)
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $total = Vote::owns()
            ->filter($filters)
            ->count();

        $votes->transform(function ($vote) {
            return [
                'id' => $vote->id,
                'title' => $vote->title,
                'amount' => $vote->amount,
                'state_name' => $vote->stateName(),
                'begin_date' => $vote->begin_date,
                'end_date' => $vote->end_date,
                'created_at' => $vote->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $vote->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        $ds = New DataSource();
        $ds->rows = $votes;
        $ds->total = $total;

        return Response::json($ds);
    }

    public function state()
    {
        Vote::state(request()->all());
    }
}
