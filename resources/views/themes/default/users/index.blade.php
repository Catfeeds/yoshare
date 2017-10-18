@extends('themes.default.layouts.master')
@section('title', '用户中心-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/user.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="u-wrapper">
        <div class="head">
            <div class="avatar">
                <i></i><img src="#" alt="avatar"></div>
            <h3>哎呦喂</h3>
        </div>
        <div class="order">
            <div class="content">
                <div class="o-title clear">全部订单 <a href="/orders" alt="orders">查看全部订单 ></a></div>
                <ul class="clear">
                    <li><a href="#">待支付</a></li>
                    <li><a href="#">待发货</a></li>
                    <li><a href="#">待收货</a></li>
                    <li><a href="#">待归还</a></li>
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
                    <li><a href="/address">管理收货地址</a></li>
                    <li><a href="/user/vip">高级用户管理</a></li>
                    <li><a href="/user/phone">绑定我的手机</a></li>
                </ul>
            </div>
        </div>
        <div class="set">
            <div class="content">
                <ul>
                    <li class="s-user"><a href="#">个人设置</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>

    </script>
@endsection