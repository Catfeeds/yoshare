@extends('themes.mobile.layouts.master')
@section('title', '提交订单--游享')
@section('css')
    <link href="{{ url('css/order.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('css/cart.css') }}" rel="stylesheet" type="text/css">
    <style>
        ul.transt li{
            height: 46px;
            line-height: 46px;
            margin: 0 5%;
            font-size: 30px;
        }
        ul.amount li{
            height: 64px;
            line-height: 64px;
            margin: 0 5%;
            font-size: 30px;
        }
        ul li .l-left{
            float: left;
        }
        ul li .l-right{
            float: right;
        }
    </style>
@endsection

@section('content')

    @include('themes.mobile.orders.header')

    <div class="c-goods">
        @if(!empty($address))
            <ul class="list">
                <li style="height: 160px; margin: 0 5%;">
                    <div class="w-addr clear">
                        <span class="receiver">收货人：<i id="name">{{ $address->name }}</i></span>
                        <span class="tel">电　话：<i id="phone">{{ $address->phone }}</i></span>
                    </div>
                    <div class="addr-detail">
                        <span id="address">{{ $address->detail }}</span>
                    </div>
                </li>
            </ul>
            <input type="hidden" name="address_id" value="{{ $address->id }}" id="aid">
        @else
            <div class="a-wrapper" style="padding: 20px 0;"><a href="#" onclick="addAddr()" class="a-default" style="background: #fff;">添加收货地址</a></div>
            <input type="hidden" name="address_id" value="0" id="aid">
        @endif
        <input type="hidden" id="id" value="{{ $carts['id'] }}" name="id" />
        <ul class="p-ul">
            @foreach( $goodses as $goods )
            <li>
                <div class="g-img"><img src='{{ $goods->image_url }}' alt="商品图片"></div>
                <div class="g-desc" style="width: 38%">
                    <h4 class="name">{{ $goods->name }}</h4>
                    <div class="subtitle">{{ $goods->subtitle }}</div>
                    <div class="price">￥{{ $goods->sale_price }}/月</div>
                </div>
                <div class="num" style="margin-left: 6.7%">
                    <input type="text" class="i-num" value="{{ $carts['numbers'][$goods->id] }}" disabled>
                    <input type="hidden" value="{{ $goods->id }}" class="goods_id">
                </div>
                <div class="clear"></div>
            </li>
            @endforeach
        </ul>
        <ul class="transt">
            <li>
                <div class="l-left">支付配送</div>
                <div class="l-right">在线支付</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="l-right">快递货运</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="l-right">工作日、双休日与节假日均可送货</div>
                <div class="clear"></div>
            </li>
        </ul>
        <ul class="amount">
            <li>
                <div class="l-left">
                    商品金额
                    @if(!empty($carts['first_order']))
                        <span style="color:red">(首单半价)</span>
                    @endif
                </div>
                <div class="l-right">共<span style="color: red;">({{ $carts['number'] }})</span>件商品    合计：￥<span style="color: red;" id="total_price">{{ $carts['total_price'] }}</span></div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="l-left">运费</div>
                <div class="l-right">￥0.00</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="l-left">押金冻结</div>
                <div class="l-right" style="color: #00a0e9;">￥00.00</div>
                <div class="clear"></div>
            </li>
        </ul>
    </div>
@endsection

@section('js')
<script>
    var addr_back = window.location.href;
    function addAddr() {
        window.location.href='/address/create?addrBack='+addr_back;
    }
</script>
@endsection

