@extends('themes.mobile.layouts.master')
@section('title', '高级用户中心-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/member.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/cart.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')

    @include('themes.mobile.members.header')

    <div class="u-wrapper">
        <div class="vip-text">
            <h3>什么是高级用户？</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                <p>黄金会员 押金金额 300元  每次可租盘上限：1本</p>
                <p>铂金会员 押金金额 600元  每次可租盘上限：2本</p>
                <p>钻石会员 押金金额 900元  每次可租盘上限：3本</p>
             </div>
        </div>
        <div class="a-wrapper"><a href="#" class="a-default">立即成为VIP</a></div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection