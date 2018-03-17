@extends('themes.mobile.master')
@section('title', '编辑收货地址-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/address.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')

    @include('themes.mobile.layouts.header')

    <div class="u-wrapper">
        <div class="address">
            {!! Form::model($address, ['id' => 'form', 'method' => 'PATCH', 'url' => '/address/'.$address->id, 'class' => 'form-horizontal']) !!}
                {!! csrf_field() !!}
            @include('themes.mobile.address.form')
            <div class="b-center"><button type="submit">编辑</button></div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection