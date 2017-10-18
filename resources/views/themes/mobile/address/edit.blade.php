@extends('templates.master')
@section('title', '添加收货地址-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/address.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="u-wrapper">

        @include('templates.back')

        <div class="address">
            <h3>添加收货地址</h3>
            {!! Form::model($address, ['id' => 'form', 'method' => 'PATCH', 'action' => ['AddressController@update', $address->id],'class' => 'form-horizontal']) !!}
                {!! csrf_field() !!}
                @include('templates.address.form')
            {!! Form::close() !!}
        </div>
        <div class="b-center"><button type="submit">添加</button></div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection