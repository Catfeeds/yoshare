@extends('themes.mobile.layouts.master')
@section('title', '游戏分类--北京优享科技有限公司')

@section('css')
    <link href="{{ url('css/list.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    @include('themes.mobile.widgets.header')
    <div class="c-wrapper">
        <div class="category">
            <ul>
                <li><a href="/goods/category-2.html">射击游戏</a></li>
                <li><a href="/goods/category-3.html">格斗游戏</a></li>
                <li><a href="/goods/category-4.html">赛车游戏</a></li>
                <li><a href="/goods/category-5.html">体育游戏</a></li>
                <div class="clear"></div>
            </ul>
            <ul>
                <li><a href="/goods/category-6.html">动作游戏</a></li>
                <li><a href="/goods/category-7.html">冒险游戏</a></li>
                <li><a href="/goods/category-8.html">角色扮演</a></li>
                <li><a href="/goods/category-9.html">沙盒游戏</a></li>
                <div class="clear"></div>
            </ul>
        </div>
        <div class="products">
            @foreach($category->goods()->where('state', \App\Models\Goods::STATE_PUBLISHED)->orderBy('sort')->get() as $goods)
                @if($loop->index%2 == 0)
                    <ul>
                        @endif
                        <li>
                            <a href="/goods/detail-{{ $goods->id }}.html"><img src="{{ $goods->image_url }}" alt="{{ $goods->name }}"></a>
                            <div class="summary">{{ $goods->subtitle }}</div>
                            <a href="/goods/detail-{{ $goods->id }}.html"><h4>{{ $goods->name }}</h4></a>
                            <p class="price">￥{{ $goods->sale_price }}/月</p>
                        </li>
                        @if($loop->index%2 == 1)
                            <div class="clear"></div>
                    </ul>
                @endif
            @endforeach
        </div>
    </div>
@endsection
@section('js')
    <script>

    </script>
@endsection