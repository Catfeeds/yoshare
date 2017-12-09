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
                公司简介, 北京优享公司致力于ps游戏卡运营。公司简介, 北京优享公司致力于ps游戏卡运营。
                公司简介, 北京优享公司致力于ps游戏卡运营。公司简介, 北京优享公司致力于ps游戏卡运营。
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection