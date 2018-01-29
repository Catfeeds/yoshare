@extends('themes.mobile.layouts.master')
@section('title', '订单页--北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/order.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    @include('themes.mobile.orders.header')
    <ul>
        <li class="active"><a href="#">全部</a></li>
        <li><a href="#">待付款</a></li>
        <li><a href="#">待发货</a></li>
        <li><a href="#">待收货</a></li>
        <li><a href="#">待评价</a></li>
    </ul>

@endsection

@section('js')

@endsection

