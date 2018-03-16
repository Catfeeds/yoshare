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
            <h3>会员等级简介</h3>
            <div class="content" style="padding-top: 40px;">
                <p><b style="color: #ffcc42">黄金会员</b> </p><p style="text-indent: 80px">押金金额 300元 每次可租盘上限：<b>1本</b></p>
                <p><b style="color: #33ff00">铂金会员</b> </p><p style="text-indent: 80px">押金金额 600元 每次可租盘上限：<b>2本</b></p>
                <p><b style="color: #0037ff">钻石会员</b> </p><p style="text-indent: 80px">押金金额 900元 每次可租盘上限：<b>3本</b></p>
             </div>
        </div>
        <div class="a-wrapper"><a href="/vip/pay" class="a-default">立即成为VIP</a></div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection