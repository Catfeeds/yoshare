@extends('themes.mobile.master')
@section('title', '关于我们-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/member.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/cart.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="u-wrapper">

        @include('themes.mobile.members.header')

        <div class="vip-text">
            <h3 style="text-align: center">北京优享科技有限公司</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                YO享旨在为玩家搭建一个诚信、有保障的游戏租赁平台，为玩家提供便宜，便捷，省心的游玩方式。用户只需要通过平台极速下单，即可获得商品使用权，有效降低游玩成本90%，同时以公司对用户形式，使客户租赁风险为“0”。            </div>
        </div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection