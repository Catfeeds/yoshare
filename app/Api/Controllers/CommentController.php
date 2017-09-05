<?php

namespace App\Api\Controllers;

use App\Models\Comment;
use App\Models\Content;
use App\Models\File;
use App\Models\Option;
use EasyWeChat\Message\Article;
use Exception;
use Request;

class CommentController extends BaseController
{
    public function transform($comment)
    {
        return [
            'id' => $comment->id,
            'children' => $comment->children()->where('state', Comment::STATE_PASSED)->orderBy('id', 'desc')->get()->transform(function ($child) {
                return [
                    'id' => $child->id,
                    'content' => $child->content,
                    'likes' => $child->likes,
                    'member_id' => $child->member->id,
                    'member_name' => $child->member->name,
                    'member_type' => $child->member->type,
                    'nick_name' => $child->member->nick_name,
                    'avatar_url' => get_image_url($child->member->avatar_url),
                    'time' => $child->created_at->toDateTimeString(),
                ];
            }),
            'images' => $comment->files()->where('type', File::TYPE_IMAGE)->transform(function ($file) use ($comment) {
                return [
                    'id' => $file->id,
                    'refer_id'=> $file->id,
                    'title' => !empty($file->title) ?: $comment->title,
                    'url' => get_image_url($file->url),
                    'summary' => $file->summary,
                ];
            }),
            'content' => $comment->content,
            'likes' => $comment->likes,
            'member_name' => $comment->member->name,
            'nick_name' => $comment->member->nick_name,
            'avatar_url' => get_image_url($comment->member->avatar_url),
            'time' => $comment->created_at->toDateTimeString(),
        ];
    }
    /**
     * @SWG\Get(
     *   path="/comments/list",
     *   summary="获取评论列表",
     *   tags={"/comments 评论"},
     *   @SWG\Parameter(name="content_id", in="query", required=true, description="内容ID", type="string"),
     *   @SWG\Parameter(name="page_size", in="query", required=true, description="分页大小", type="integer"),
     *   @SWG\Parameter(name="page", in="query", required=true, description="分页序号", type="integer"),
     *   @SWG\Response(
     *     response=200,
     *     description="查询成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到路由"
     *   )
     * )
     */
    public function lists()
    {
        $page_size = Request::get('page_size') ? Request::get('page_size') : 20;
        $page = Request::get('page') ? Request::get('page') : 1;
        $content_id = Request::get('content_id');

        $total = Comment::where('refer_id', $content_id)
            ->where('state', Comment::STATE_PASSED)
            ->count();

        $comments = Comment::with('member')
            ->where('refer_id', $content_id)
            ->where('state', Comment::STATE_PASSED)
            ->orderBy('id', 'desc')
            ->forPage($page, $page_size)
            ->get();

        $comments->transform(function ($comment) {
            return $this->transform($comment);
        });

        return $this->response([
            'status_code' => 200,
            'message' => 'success',
            'total' => $total,
            'data' => $comments
        ]);
    }

    /**
     * @SWG\Get(
     *   path="/comments/create",
     *   summary="发表评论",
     *   tags={"/comments 评论"},
     *   @SWG\Parameter(name="content_id", in="query", required=true, description="内容ID", type="string"),
     *   @SWG\Parameter(name="content", in="query", required=true, description="评论内容", type="string"),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="评论成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   )
     * )
     */
    public function create()
    {
        $content_id = Request::get('content_id');
        $commentContent = Request::get('content');
        \Log::debug(Request::get('token'));

        try {
            $member = \JWTAuth::parseToken()->authenticate();
            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        //根据内容类型获取标题
        $content = Article::find($content_id);

        //增加评论数
        $content->comments += 1;
        $content->save();

        //是否免审核
        $option = Option::getValue(Option::COMMENT_REQUIRE_PASS);

        //增加评论记录
        $comment = new Comment();
        $comment->site_id = $content->site_id;
        $comment->refer_id = $content->id;
        $comment->refer_type = $content->id;
        $comment->content_title = $content->title;
        $comment->content = $commentContent;
        $comment->member_id = $member->id;
        $comment->ip = get_client_ip();
        $comment->state = $option ? Comment::STATE_NORMAL : Comment::STATE_PASSED;

        $comment->save();


        return $this->responseSuccess();
    }

    /**
     * @SWG\Get(
     *   path="/comments/like",
     *   summary="评论点赞",
     *   tags={"/comments 评论"},
     *   @SWG\Parameter(name="id", in="query", required=true, description="评论ID", type="string"),
     *   @SWG\Parameter(name="flag", in="query", required=true, description="标记(1:点赞,0:取消点赞）", type="integer"),
     *   @SWG\Parameter(name="token", in="query", required=true, description="token", type="string"),
     *   @SWG\Response(
     *     response=200,
     *     description="评论成功"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="没有找到"
     *   )
     * )
     */
    public function like()
    {
        $id = Request::get('id');
        $flag = Request::get('flag');

        try {
            $member = \JWTAuth::parseToken()->authenticate();
            if (!$member) {
                return $this->responseError('无效的token,请重新登录');
            }
        } catch (Exception $e) {
            return $this->responseError('无效的token,请重新登录');
        }

        //根据内容类型获取标题
        $comment = Comment::find($id);

        if (empty($comment)) {
            return $this->responseError('无此评论ID');
        }

        if ($flag) {
            $comment->increment('likes');
        }
        else{
            $comment->decrement('likes');
        }


        return $this->responseSuccess();
    }
}