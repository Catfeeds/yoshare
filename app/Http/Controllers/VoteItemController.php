<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\VoteItem;
use Request;
use Response;

class VoteItemController extends Controller
{
    public function show($vote_id)
    {
        return view('admin.votes.items', compact('vote_id'));
    }

    public function update($id)
    {
        $input = Request::all();
        $item = VoteItem::find($id);
        $item->count = $input['count'];

        if ($item == null) {
            return;
        }
        $item->save();
    }

    public function table($vote_id)
    {
        $vote = Vote::find($vote_id);
        $items = $vote->items()->orderBy('id', 'desc')->get();
        $amount = $vote->items->sum('count');

        $items->transform(function ($item) use ($amount) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'percent' => $amount == 0 ? 0 : round(($item->count / $amount) * 100) . '%',
                'count' => $item->count,
                'sort' => $item->sort,
            ];
        });

        $ds = new \stdClass();
        $ds->data = $items;

        return Response::json($ds);
    }
}
