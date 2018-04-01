@extends('themes.mobile.master')
@section('title', '添加收货地址-游享')
@section('css')
    <link href="{{ url('css/address.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')

    @include('themes.mobile.layouts.header')

    <div class="u-wrapper">
        <div class="address">

            {!! Form::open(['url' => '/address', 'method' => 'post']) !!}
                {!! csrf_field() !!}
                @include('themes.mobile.address.form')
                <div class="b-center"><button type="submit">添加</button></div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection