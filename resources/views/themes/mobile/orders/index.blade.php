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
                    @if($order['state'] == \App\Models\Order::STATE_NOPAY)
                        <div class="action">
                            <a class="c-button" onclick="cancle({{ $order->id }})">取消订单</a>
                        </div>
                    @endif
                    @if($order['state'] == \App\Models\Order::STATE_SUCCESS)
                        <div class="action">
                            <a class="c-button" onclick="orderDel({{ $order->id }})">删除订单</a>
                        </div>
                    @elseif($order['state'] == \App\Models\Order::STATE_RETURN)
                        @if(!empty($order->back_ship_num))
                            <div class="action">
                                <a class="c-button" onclick="Return({{ $order->id }}, {{ $order->back_ship_num }})">已申请归还</a>
                            </div>
                        @else
                            <div class="action">
                                <a class="c-button" onclick="Return({{ $order->id }})">申请归还</a>
                            </div>
                        @endif
                    @elseif($order['state'] == \App\Models\Order::STATE_PAID)
                        <div class="action">
                            <a class="c-button" onclick="urge()">催促发货</a>
                        </div>
                    @elseif($order['state'] == \App\Models\Order::STATE_NOPAY)
                        <div class="action">
                            <a class="c-button" href="/order/pay/{{ $order->id }}">立即支付</a>
                        </div>
                    @elseif($order['state'] == \App\Models\Order::STATE_SENDED)
                        <div class="action">
                            <a class="c-button" onclick="shipNum({{ $order->ship_num }})">物流单号</a>
                        </div>
                        <div class="action">
                            <a class="c-button" onclick="received({{ $order->id }})">确认收货</a>
                        </div>
                    @elseif($order['state'] == \App\Models\Order::STATE_REFUND)
                        <div class="action">
                            <a class="c-button" onclick="orderDel({{ $order->id }})">删除订单</a>
                        </div>
                    @endif
                </li>
            </ul>
        @endforeach
    </div>
@endsection

@section('js')
    <script src="{{ url('/js/layer.js') }}"></script>
    <script src="{{ url('/js/clipboard.min.js') }}"></script>
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

    function urge() {
        layer.open({
            content: '已催促店小二，请您耐心等待~'
            ,btn: ['确定', '取消']
        });
    }

    function shipNum(num) {
        layer.open({
            title : '查看物流单号'
            ,content: num
        });

    }

    function received(orderId) {

        var state = {{ \App\Models\Order::STATE_RETURN }};

        layer.open({
            content: '您确认已收到游戏盘了吗？',
            btn: ['确认', '取消'],
            yes: function(index, layero) {
                $.ajax({
                    url  : '/order/edit/'+orderId,
                    type : 'get',
                    data : {
                        'state'           : state
                    },
                    success:function(data){
                        msg = data.message;
                        statusCode = data.status_code;

                        if(statusCode == 200){
                            window.location.href='/order/lists';
                        }
                    }
                });
            }
        });

    }

    function cancle(orderId) {

        var state = {{ \App\Models\Order::STATE_CLOSED }};

        layer.open({
            content: '您确定取消订单吗？',
            btn: ['确认', '取消'],
            yes: function(index, layero) {
                $.ajax({
                    url  : '/order/edit/'+orderId,
                    type : 'get',
                    data : {
                        'state'           : state
                    },
                    success:function(data){
                        msg = data.message;
                        statusCode = data.status_code;

                        if(statusCode == 200){
                            window.location.href='/order/lists';
                        }
                    }
                });
            }
        });

    }

    function Return(orderId, back_ship_num) {

        if(typeof back_ship_num == 'undefined'){
            back_ship_num = '';
        }

        var html  = '<p>收件人：付先生</p>'+
                    '<p>电话：010-50976059</p>'+
                    '<p>邮编：102600</p>'+
                    '<p>回寄地址：北京市大兴区鸿坤曦望山2号楼2单元304 </p>'+
                    '<form method="get" action="/order/edit/'+orderId+'">'+
                        '物流单号：<input type="text" name="back_ship_num" value="'+back_ship_num+'" class="i-form" style="padding-left: 20px;width: 70%;"/>'+
                        '<button type="submit" style="width: 20%;margin-top: 30px;height: 106px;border-radius: 10px;">提交</button>'+
                    '</form>';
        layer.open({
            type: 1
            ,title: '申请归还'
            ,content: html
            ,anim: 'up'
            ,style: 'position:fixed; top:200px; left:4%; width: 92%; height: 750px; border:none;'
        });

    }
</script>
@endsection

