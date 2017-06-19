<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContentRequest;
use App\Models\Category;
use App\Models\Content;
use App\Models\Keyword;
use Gate;
use Request;
use Response;

class ContentController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        if (Gate::denies('@content')) {
            $this->middleware('deny403');
        }
        $page = request('page') ?: 1;
        $fields = json_decode(json_encode(config('site.model.1.fields')));

        return view('contents.index', compact('page', 'fields'));
    }

    public function create($category_id)
    {
        if (Gate::denies('@content-create')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $list_keywords = Keyword::orderBy('times', 'desc')->pluck('name', 'name')->toArray();

        $fields = config('site.model.1.fields');
        $fields = json_decode(json_encode($fields));
        $fields = array_sort($fields, function ($field) {
            return $field->editor->index;
        });
        $tabs = [
            [
                'name' => 'info',
                'alias' => '基本信息',
                'fields' => array_values(array_filter($fields, function ($field) {
                    return $field->editor->show && $field->editor->tab == 'info';
                }))
            ],
            [
                'name' => 'content',
                'alias' => '正文',
                'fields' => array_values(array_filter($fields, function ($field) {
                    return $field->editor->show && $field->editor->tab == 'content';
                }))
            ]
        ];
        $tabs = json_decode(json_encode($tabs));

        return view('contents.create', compact('page', 'category_id', 'list_keywords', 'tabs'));
    }

    public function store(ContentRequest $request)
    {
        $input = $request->all();

        $content = Content::stores($input);

        \Log::debug('contents store id: ' . $content->id);
        \Log::debug('ip: ' . get_client_ip());

        \Session::flash('flash_success', '添加成功');
        return redirect('/contents?category_id=' . $content->category_id);
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

    public function edit($id)
    {
        if (Gate::denies('@content-edit')) {
            \Session::flash('flash_warning', '无此操作权限');
            return redirect()->back();
        }

        $page = request('page');

        $content = Content::find($id);
        if ($content == null) {
            \Session::flash('flash_warning', '无此记录');
            return redirect('/contents');
        }
        $category_id = $content->category_id;

        //图集地址
        $content->images = implode(',', $content->items()->orderBy('sort')->pluck('url')->toArray());

        $selected_keywords = explode(' ', $content->keywords);
        $list_keywords = Keyword::orderBy('times', 'desc')->pluck('name', 'name')->toArray();
        if (empty($list_keywords) && !empty($list_keywords)) {
            foreach ($selected_keywords as $keyword) {
                $list_keywords[$keyword] = $keyword;
            }
        }

        $fields = config('site.model.1.fields');
        $fields = json_decode(json_encode($fields));
        $fields = array_sort($fields, function ($field) {
            return $field->editor->index;
        });
        $tabs = [
            [
                'name' => 'info',
                'alias' => '基本信息',
                'fields' => array_values(array_filter($fields, function ($field) {
                    return $field->editor->show && $field->editor->tab == 'info';
                }))
            ],
            [
                'name' => 'content',
                'alias' => '正文',
                'fields' => array_values(array_filter($fields, function ($field) {
                    return $field->editor->show && $field->editor->tab == 'content';
                }))
            ]
        ];
        $tabs = json_decode(json_encode($tabs));

        return view('contents.edit', compact('page', 'category_id', 'content', 'selected_keywords', 'list_keywords', 'tabs'));
    }

    public function update($id, ContentRequest $request)
    {
        $input = $request->all();

        $content = Content::updates($id, $input);

        \Session::flash('flash_success', '修改成功!');
        return redirect('/contents?category_id=' . $content->category_id . '&page=' . $input['page']);
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
        return view("templates.contents.$template", compact('content'));
    }

    public function lists($category_id)
    {
        $category = Category::find($category_id);
        if (empty($category)) {
            abort(404);
        }
        return view("templates.contents.list", compact('category'));
    }

    public function slug($slug)
    {
        $content = Content::where('slug', $slug)
            ->first();
        if (empty($content)) {
            abort(404);
        }

        $template = $content->category->template;
        return view("templates.contents.$template", compact('content'));
    }

    public function comment($content_id)
    {
        return view('contents.comment', compact('content_id'));
    }

    public function state($state)
    {
        Content::state($state, '@content');
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
        if (Gate::denies('@content-top')) {
            \Session::flash('flash_warning', '无此操作权限');
            return;
        }

        Content::top($id);
    }

    public function push()
    {
        if (Gate::denies('@content-push')) {
            \Session::flash('flash_warning', '无此操作权限');
            return Response::json([
                'status_code' => 401,
            ]);
        }

        $input = Request::all();

        return Content::jpush($input);
    }

    public function tag($id)
    {
        if (Gate::denies('@content-tag')) {
            \Session::flash('flash_warning', '无此操作权限');
            return;
        }

        Content::tag($id, Content::TAG_SIDE);
    }

    public function recommend($id)
    {
        if (Gate::denies('@content-tag')) {
            \Session::flash('flash_warning', '无此操作权限');
            return;
        }

        Content::tag($id, Content::TAG_RECOMMEND);
    }

    public function sort()
    {
        if (Gate::denies('@content-sort')) {
            \Session::flash('flash_warning', '无此操作权限');
            return Response::json([
                'status_code' => 401,
            ]);
        }

        return Content::sort();
    }
}
