@extends('themes.mobile.master')
@section('title', '添加收货地址-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/address.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="u-wrapper">

        @include('themes.mobile.address.header')

        <div class="address">

            {!! Form::open(['url' => '/address', 'method' => 'post']) !!}
                {!! csrf_field() !!}
                @include('themes.mobile.address.form')
            {!! Form::close() !!}
        </div>
        <div class="b-center"><button type="submit">添加</button></div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection