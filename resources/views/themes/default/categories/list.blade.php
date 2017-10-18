@extends('templates.layouts.master')
@section('title', '北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/list.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
<div class="category">
    <ul>
        <li><a href="/categories/list/7">射击游戏</a></li>
        <li><a href="/categories/list/8">格斗游戏</a></li>
        <li><a href="/categories/list/9">赛车游戏</a></li>
        <li><a href="/categories/list/10">体育游戏</a></li>
        <div class="clear"></div>
    </ul>
    <ul>
        <li><a href="/categories/list/11">动作游戏</a></li>
        <li><a href="/categories/list/12">冒险游戏</a></li>
        <li><a href="/categories/list/13">角色扮演</a></li>
        <li><a href="/categories/list/14">沙盒游戏</a></li>
        <div class="clear"></div>
    </ul>
</div>
<div class="products">
    @foreach($category->contents()->where('state', \App\Models\Content::STATE_PUBLISHED)->orderBy('sort')->get() as $content)
        @if($loop->index%2 == 0)
            <ul>
                @endif
                <li>
                    <a href="/contents/show/{{ $content->id }}"><img src="{{$content->image_url}}" alt="{{$content->title}}"></a>
                    <div class="summary">{{$content->summary}}</div>
                    <a href="/contents/show/{{ $content->id }}"><h4>{{$content->title}}</h4></a>
                    <p class="price">{{$content->subtitle}}</p>
                </li>
                @if($loop->index%2 == 1)
                    <div class="clear"></div>
            </ul>
        @endif
    @endforeach
</div>

@endsection
@section('js')
    <script>

    </script>
@endsection