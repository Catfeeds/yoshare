@extends('templates.master')
@section('title', '高级用户中心-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/user.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="u-wrapper">

        @include('templates.back')

        <div class="vip-text">
            <div class="content">
                <h3>什么是高级用户？</h3>
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