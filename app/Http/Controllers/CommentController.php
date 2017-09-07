<?php

namespace App\Http\Controllers;

use App\Models\DataSource;
use App\Models\Comment;
use Gate;
use Request;
use Response;

class CommentController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        if (Gate::denies('@comment')) {
            $this->middleware('deny403');
        }

        return view('admin.comments.index');
    }

    public function update($id)
    {
        $comment = Comment::find($id);

        if ($comment == null) {
            return;
        }

        $comment->update(Request::all());
    }

    public function destroy($id)
    {
        if (Gate::denies('@comment-delete')) {
            \Session::flash('flash_warning', '无此操作权限');
            return;
        }

        $comment = Comment::find($id);
        $comment->state = Comment::STATE_DELETED;
        $comment->save();
        \Session::flash('flash_success', '删除成功');
    }

    public function pass($id)
    {
        if (Gate::denies('@comment-pass')) {
            \Session::flash('flash_warning', '无此操作权限');
            return;
        }

        $comment = Comment::find($id);
        $comment->state = Comment::STATE_PASSED;
        $comment->save();
        \Session::flash('flash_success', '审核成功');
    }

    public function table()
    {
        $filter = Request::all();
        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $comments = Comment::owns()
            ->filter($filter)
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $count = Comment::owns()
            ->filter($filter)
            ->count();

        $comments->transform(function ($comment) {
            return [
                'id' => $comment->id,
                'refer_id' => $comment->refer_id,
                'content' => $comment->content,
                'nick_name' => empty($comment->member) ? $comment->user->name : $comment->member->nick_name,
                'ip' => $comment->ip,
                'likes' => $comment->likes,
                'state' => $comment->state,
                'state_name' => $comment->stateName(),
                'created_at' => empty($comment->created_at) ?: $comment->created_at->toDateTimeString(),
                'updated_at' => empty($comment->updated_at) ?: $comment->updated_at->toDateTimeString(),
            ];
        });

        $ds = New DataSource();
        $ds->total = $count;
        $ds->rows = $comments;

        return Response::json($ds);
    }

    public function state()
    {
        Comment::state(request()->all());
    }

}
