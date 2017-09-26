<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteRequest;
use App\Models\Item;
use App\Models\Vote;
use Auth;
use Gate;

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
            $vote->items()->create([
                'type' => Item::TYPE_IMAGE,
                'title' => $item_title,
                'url' => $data['item_url'][$k],
                'summary' => $data['summary'][$k],
                'sort' => $k,
            ]);
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

        if (array_key_exists('item_id', $data)) {
            $vote->items()->whereNotIn('id', $data['item_id'])->delete();
        }

        foreach ($data['item_title'] as $k => $item_title) {
            if (!empty(trim($item_title)) || !empty(trim($data['item_url'][$k]))) {
                if (!isset($data['item_id'][$k])) {
                    $vote->items()->create([
                        'type' => Item::TYPE_IMAGE,
                        'title' => $item_title,
                        'url' => $data['item_url'][$k],
                        'summary' => $data['summary'][$k],
                        'sort' => $k,
                    ]);
                } else {
                    $item = $vote->items()->where('id', $data['item_id'][$k])->first();
                    $item->update([
                        'type' => Item::TYPE_IMAGE,
                        'title' => $item_title,
                        'url' => $data['item_url'][$k],
                        'summary' => $data['summary'][$k],
                        'sort' => $k,
                    ]);
                }
            }
        }

        return redirect('/admin/votes')->with('item', $item)->with('flash_success', '编辑成功！');
    }

    public function table()
    {
        return Vote::table();
    }

    public function sort()
    {
        return Vote::sort();
    }

    public function state()
    {
        Vote::state(request()->all());
    }
}
