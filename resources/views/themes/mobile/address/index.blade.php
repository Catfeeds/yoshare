@extends('themes.mobile.master')
@section('title', '我的收货地址-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/address.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="u-wrapper">

        @include('themes.mobile.address.header')

        <div class="title">我的收货地址</div>
        <ul class="list">
            <li>
                <div class="w-addr clear">
                    <span class="receiver">收货人：yoyo</span>
                    <span class="tel">17600400049</span>
                </div>
                <div class="addr-detail">
                    <span>北京市朝阳区广渠路中水电大厦</span>
                </div>
                <div class="addr-set clear">
                    <a class="a-left" href="#" onclick="setAddr()">默认地址</a>
                    <a href="/address/delete" class="a-right">删除</a>
                    <a href="/address/edit" class="a-right" style="margin-right: 143px;">编辑</a>
                </div>
            </li>
            <li>
                <div class="w-addr clear">
                    <span class="receiver">收货人：yoyo</span>
                    <span class="tel">17600400049</span>
                </div>
                <div class="addr-detail">
                    <span>北京市朝阳区广渠路中水电大厦</span>
                </div>
                <div class="addr-set clear">
                    <a class="a-left" href="#" onclick="setAddr()">默认地址</a>
                    <a href="/address/delete" class="a-right">删除</a>
                    <a href="/address/edit" class="a-right" style="margin-right: 143px;">编辑</a>
                </div>
            </li>
        </ul>
        <div class="a-wrapper"><a href="/address/create" class="a-default">添加收货地址</a></div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection