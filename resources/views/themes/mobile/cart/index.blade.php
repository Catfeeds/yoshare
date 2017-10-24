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
            <li>
                <input type="radio" class="c-radio" >
                <div class="g-img"><img src="{{ url('images/goods/g-img.png') }}" alt="商品图片"></div>
                <div class="g-desc">
                    <h4 class="name">瑞奇与叮当</h4>
                    <div class="subtitle">索尼（sony）ps4【国行游戏】</div>
                    <div class="price">￥30/月</div>
                </div>
                <div class="num">
                    <i class="i-sub"></i>
                    <input type="text" class="i-num" value="">
                    <i class="i-add"></i>
                </div>
                <div class="action">
                    <a class="c-button">删除</a>
                </div>
                <div class="clear"></div>
            </li>
        </ul>
    </div>
@endsection

@section('js')

@endsection

