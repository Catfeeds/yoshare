<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContentRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Content;
use App\Models\Member;
use App\Models\Pasture;
use Auth;
use Gate;
use Request;
use Response;

class ContentController extends Controller
{
    public function __construct()
    {
    }

    public function destroy($id)
    {
        $content = Content::find($id);

        if ($content == null) {
            \Session::flash('flash_warning', '无此记录');
            return;
        }

        $content->state = Content::STATE_DELETED;

        $content->save();

        \Session::flash('flash_success', '删除成功');
    }

    public function save($id)
    {
        $content = Content::find($id);

        if (empty($content)) {
            return;
        }

        $content->update(Request::all());
    }

    public function show($id)
    {
        $content = Content::findOrFail($id);
        if (empty($content)) {
            abort(404);
        }

        $template = $content->category->template;
        return view("admin.templates.contents.$template", compact('content'));
    }

    public function lists($category_id)
    {
        $category = Category::find($category_id);
        if (empty($category)) {
            abort(404);
        }
        return view("admin.templates.contents.list", compact('category'));
    }

    public function slug($slug)
    {
        $content = Content::where('slug', $slug)
            ->first();
        if (empty($content)) {
            abort(404);
        }

        $template = $content->category->template;
        return view("admin.templates.contents.$template", compact('content'));
    }

    public function comment($content_id)
    {
        return view('admin.contents.comment', compact('content_id'));
    }

    public function copy()
    {
        Content::copy();
    }

    public function categories()
    {
        return Response::json(Category::tree(Category::STATE_ENABLED));
    }

    public function table()
    {
        return Content::table();
    }

    public function top($id)
    {
        if (Gate::denies('@article-top')) {
            \Session::flash('flash_warning', '无此操作权限');
            return;
        }

        Content::top($id);
    }

    public function push()
    {
        if (Gate::denies('@article-push')) {
            \Session::flash('flash_warning', '无此操作权限');
            return Response::json([
                'status_code' => 401,
            ]);
        }

        $input = Request::all();

        return Content::jpush($input);
    }

    public function sort()
    {
        if (Gate::denies('@article-sort')) {
            \Session::flash('flash_warning', '无此操作权限');
            return Response::json([
                'status_code' => 401,
            ]);
        }

        return Content::sort();
    }
}
