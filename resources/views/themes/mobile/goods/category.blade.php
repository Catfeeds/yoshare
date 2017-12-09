@extends('themes.mobile.layouts.master')
@section('title', '游戏分类--北京优享科技有限公司')

@section('css')
    <link href="{{ url('css/list.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    @include('themes.mobile.widgets.header')
    <div class="c-wrapper">
        <div class="category">
            @foreach($lists as $key => $val)
                @if($key%4 == 2)
                    <ul>
                @endif
                    <li @if($category->id == $key)
                            class = "active";
                        @endif >
                        <a href="/goods/category-{{ $key }}.html">{{ $val }}</a>
                    </li>

                @if($key%4 == 1)
                        <div class="clear"></div>
                    </ul>
                @endif
            @endforeach
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