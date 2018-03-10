@extends('themes.mobile.layouts.master')
@section('title', '用户中心-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/member.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="u-wrapper" style="padding-bottom: 180px;">
        <div class="head">
            <div class="avatar">
                <i></i><img src="#" alt="avatar"></div>
            <h3>{{ $member->username }}</h3>
        </div>
        <div class="order">
            <div class="content">
                <div class="o-title clear">全部订单 <a href="/order/lists" alt="orders">查看全部订单 ></a></div>
                <ul class="clear">
                    <li><a href="/order/lists/nopay">待支付</a></li>
                    <li><a href="/order/lists/nosend">待发货</a></li>
                    <li><a href="/order/lists/sended">待收货</a></li>
                    <li><a href="/order/lists/success">已完成</a></li>
                </ul>
            </div>
        </div>
        <div class="wallet">
            <div class="content">
                <div class="w-title">我的钱包</div>
                <ul class="clear">
                    <li><h5>0</h5><a href="/wallet/deposit">押金</a></li>
                    <li><h5>￥0</h5><a href="/wallet/balance">余额</a></li>
                    <li><h5>0</h5><a href="/wallet/coupon">优惠券</a></li>
                </ul>
            </div>
        </div>
        <div class="set">
            <div class="content">
                <ul>
                    <li><a href="/address/index.html">管理收货地址</a></li>
                    <li><a href="/member/vip">高级用户管理</a></li>
                    <li><a href="/member/phone">绑定我的手机</a></li>
                </ul>
            </div>
        </div>
        <div class="set">
            <div class="content">
                <ul>
                    <li class="s-user"><a href="/system">系统设置</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>

    </script>
@endsection