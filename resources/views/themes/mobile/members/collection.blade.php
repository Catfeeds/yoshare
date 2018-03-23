@extends('themes.mobile.layouts.master')
@section('title', '收藏页--北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/order.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('css/cart.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    @include('themes.mobile.members.header')

    <div class="c-goods">
        <ul>
            @foreach( $goodses as $goods )
                <li class="cart_li" onclick="cjump({{ $goods->id }})">
                    <div class="g-img"><img src='{{ $goods->image_url }}' alt="商品图片"></div>
                    <div class="g-desc" style="width: 60%">
                        <h4 class="name">{{ $goods->name }}</h4>
                        <div class="subtitle">{{ $goods->subtitle }}</div>
                        <div class="price">￥{{ $goods->sale_price }}/月</div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

@section('js')
    <script src="{{ url('/js/layer.js') }}"></script>
    <script>
        function cjump(id) {
            location.href = '/goods/detail-'+id+'.html';
        }
    </script>
@endsection

