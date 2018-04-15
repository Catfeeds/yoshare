@extends('themes.mobile.layouts.master')
@section('title', '游戏分类--游享')

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
            @foreach($goodses as $goods)
                @if($loop->index%2 == 0)
                    <ul>
                        @endif
                        <li>
                            <a href="/goods/detail-{{ $goods->id }}.html"><img src="{{ $goods->image_url }}" alt="{{ $goods->name }}"></a>
                            <div class="summary">
                                <div>
                                    <i class="view"></i><span>{{ $goods->view_num }}</span>
                                    <i @if($goods->favorite == \App\Models\Goods::FAVORITE_YES)
                                       class="active"
                                       @else
                                       class="favorite"
                                            @endif
                                    ></i><span>{{ $goods->favorite_num }}</span>
                                </div>
                                <p class="price">￥{{ $goods->sale_price }}/月</p>
                            </div>
                            <a href="/goods/detail-{{ $goods->id }}.html"><h4>{{ $goods->name }}</h4></a>

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