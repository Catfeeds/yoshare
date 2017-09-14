<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteRequest;
use App\Models\Vote;
use App\Models\VoteItem;
use Auth;
use Gate;
use Request;
use App\Models\DataSource;
use Response;


class VoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if (Gate::denies('@vote')) {
            $this->middleware('deny403');
        }
    }

    public function index()
    {
        return view('votes.index');
    }

    public function destroy($id)
    {
        if (Gate::denies('@vote-delete')) {
            \Session::flash('flash_warning', '无此操作权限');
            return;
        }

        $vote = Vote::find($id);
        $vote->state = Vote::STATE_DELETED;
        $vote->save();
        \Session::flash('flash_success', '删除成功');
    }

    public function create()
    {
        if (Gate::denies('@vote-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        return view('votes.create');
    }

    public function store(VoteRequest $request)
    {
        $data = $request->all();
        $data['username'] = Auth::user()->id;
        $data['state'] = Vote::STATE_NORMAL;
        $data['site_id'] = Auth::user()->site_id;
        $vote = Vote::create($data);

        //判断有无item_title然后存入数据库
        if (array_key_exists('item_title', $data)) {
            foreach ($data['item_title'] as $item_title) {
                $data2 = [
                    'title' => $item_title,
                ];
                $vote->items()->create($data2);
            }
        }
        return redirect(route('votes.index'))->with('flash_success', '新增成功！');
    }

    public function show($id)
    {
        $site_id = Auth::user()->site_id;
        $vote = Vote::find($id);
        return view("mobile.$site_id.votes.share", compact('vote'));
    }

    public function edit($id)
    {
        if (Gate::denies('@vote-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $vote = Vote::with('items')->find($id);
        return view('votes.edit')->with('vote', $vote);
    }

    public function update(VoteRequest $request, $id)
    {
        $vote = Vote::with('items')->find($id);
        $data = $request->all();
        $data['username'] = Auth::user()->id;
        $data['state'] = Vote::STATE_NORMAL;
        $data['site_id'] = Auth::user()->site_id;
        $vote->update($data);

        //删除
        if (array_key_exists('item_id', $data)) {
            $delete_item_ids = VoteItem::where('vote_id', $id)
                ->whereNotIn('id', $data['item_id'])
                ->lists('id');
            VoteItem::destroy($delete_item_ids->toArray());
        }

        if (array_key_exists('item_title', $data)) {
            foreach ($data['item_title'] as $k => $item_title) {
                if ($item_title == '') {
                    continue;
                }
                $data2 = [
                    'title' => $item_title,
                    'vote_id' => $id
                ];
                $item = VoteItem::where('vote_id', $id)->orderBy('id')->skip($k)->take(1)->first();

                //判断存在就修改，不存在就新增
                if ($item) {
                    $item->update($data2);
                } else {
                    VoteItem::create($data2);
                }
            }
        }
        return redirect(route('votes.index'))->with('item', $item)->with('flash_success', '编辑成功！');
    }


    public function statistic($id)
    {
        $vote = Vote::find($id);
        return view('votes.show', compact('vote'));
    }

    public static function table()
    {
        $filter = Request::all();
        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $site_id = Auth::user()->site_id;

        $votes = Vote::where('site_id', $site_id)
            ->filter($filter)
            ->orderBy('is_top', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $count = Vote::where('site_id', $site_id)
            ->filter($filter)
            ->count();


        $votes->transform(function ($vote) {
            return [
                'id' => $vote->id,
                'title' => $vote->title,
                'amount' => $vote->amount,
                'state_name' => $vote->stateName(),
                'begin_date' => $vote->begin_date,
                'end_date' => $vote->end_date,
                'is_top' => $vote->is_top,
                'created_at' => $vote->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $vote->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        $ds = new DataSource();
        $ds->rows = $votes;
        $ds->total = $count;
        return Response::json($ds);

    }

    public function state($state)
    {
        $ids = Request::get('ids');

        switch ($state) {
            case Vote::STATE_DELETED:
                if (Gate::denies('@vote-delete')) {
                    \Session::flash('flash_warning', '无此操作权限');
                    return;
                }
                $state_name = '删除';
                break;
            default:
                \Session::flash('flash_warning', '操作错误!');
                return;
        }

        foreach ($ids as $id) {
            $article = Vote::find($id);

            if ($article == null) {
                \Session::flash('flash_warning', '无此记录!');
                return;
            }

            $article->state = $state;
            $article->save();
        }

        \Session::flash('flash_success', $state_name . '成功!');
    }

    public function top($id)
    {
        if (Gate::denies('@vote-top')) {
            \Session::flash('flash_warning', '无此操作权限');
            return;
        }

        $vote = Vote::find($id);
        if ($vote->is_top == 1) {
            $vote->is_top = 0;
            $vote->updated_at = date('Y-m-d H:i:s');
            $vote->save();
            \Session::flash('flash_success', '取消推荐成功');
        } else {
            $vote->is_top = 1;
            $vote->updated_at = date('Y-m-d H:i:s');
            $vote->save();
            \Session::flash('flash_success', '推荐成功');
        }
    }

}