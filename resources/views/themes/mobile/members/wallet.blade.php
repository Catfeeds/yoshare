@extends('templates.master')
@section('title', '我的钱包-游享')
@section('css')
    <link href="{{ url('css/user.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="u-wrapper">

        @include('templates.back')

        <div class="w-wrapper">
            <div class="v-wallet">0</div>
            <div class="title">我的{{$title}}(元)</div>
        </div>
        <div class="take-out">取出{{$title}}</div>
        <div class="a-wrapper"><a href="#" class="a-default">充值</a></div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection