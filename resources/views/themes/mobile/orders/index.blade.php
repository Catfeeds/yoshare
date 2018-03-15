@extends('themes.mobile.layouts.master')
@section('title', '订单页--北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/order.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('css/cart.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    @include('themes.mobile.orders.header')

    <div class="c-goods">
        @foreach($orders as $order)
            <ul>
                <span class="status">{{ \App\Models\Order::STATES[$order['state']] }}</span>
                @foreach( $order['goodses'] as $goods )
                    <li class="cart_li">
                        <div class="g-img"><img src='{{ $goods->image_url }}' alt="商品图片"></div>
                        <div class="g-desc" style="width: 60%">
                            <h4 class="name">{{ $goods->name }}</h4>
                            <div class="subtitle">{{ $goods->subtitle }}</div>
                            <div class="price">￥{{ $goods->sale_price }}/月</div>
                        </div>
                    </li>
                @endforeach
                <li class="op">
                    <div class="action">
                        <a class="c-button" onclick="orderDel({{ $order->id }})">删除订单</a>
                    </div>
                    @if($order['state'] == \App\Models\Order::STATE_NOPAY)
                    <div class="action">
                        <a class="c-button" href="/order/pay/{{ $order->id }}">立即支付</a>
                    </div>
                    @endif
                </li>
            </ul>
        @endforeach
    </div>
@endsection

@section('js')
<script src="{{ url('/js/layer.js') }}"></script>
<script>
    function orderDel(goods_id) {
        layer.open({
            content: '您确定删除此订单吗？'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                location.href = '/order/'+goods_id+'/delete';
                layer.close(index);
            }
        });
    }
</script>
@endsection

