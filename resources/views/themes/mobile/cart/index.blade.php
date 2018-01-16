@extends('themes.mobile.layouts.master')
@section('title', '购物车--北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/order.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('css/cart.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    @include('themes.mobile.cart.header')

    <div class="c-goods">
        <div class="all"><a href="javascript:void(0)" onclick="" class="all-checked"></a><span class="words">全选</span></div>
        <ul>
            @foreach( $goodses as $goods )
            <li>
                <input type="radio" class="c-radio" >
                <div class="g-img"><img src='{{ $goods->image_url }}' alt="商品图片"></div>
                <div class="g-desc">
                    <h4 class="name">{{ $goods->name }}</h4>
                    <div class="subtitle">{{ $goods->subtitle }}</div>
                    <div class="price">￥{{ $goods->sale_price }}/月</div>
                </div>
                <div class="num">
                    <i class="i-sub"></i>
                    <input type="text" class="i-num" value="{{ $carts['numbers'][$goods->id] }}">
                    <i class="i-add"></i>
                </div>
                <div class="action">
                    <a class="c-button">删除</a>
                </div>
                <div class="clear"></div>
            </li>
            @endforeach
        </ul>
    </div>
@endsection

@section('js')
    <script>

    </script>
@endsection

