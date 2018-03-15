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
                高级用户的解释, 高级用户的解释, 高级用户的 高级用户的解释, 高级用户的解释, 高级用户的 高级用户的解释, 高级用户的解
            </div>
        </div>
        <div class="a-wrapper"><a href="#" class="a-default">立即成为VIP</a></div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection