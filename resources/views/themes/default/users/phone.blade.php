@extends('templates.master')
@section('title', '绑定手机号-北京优享科技有限公司')
@section('css')
<style>
.i-form{
    height: 210px;
}
.account{
    background: url('../../images/index/account_bg.png') no-repeat 0 60px;
}
.pass{
    background: url('../../images/index/pass_bg.png') no-repeat 0 60px;
}
.phone-btn{
    top: 272px;
}
.back{
    margin-left: 0;
}
</style>
@endsection
@section('content')
    <div class="wrapper">

        @include('templates.back')

        <div class="logo"><img src="{{url('images/logo.png')}}" alt="logo"></div>
        <form class="forms" name="bind" id="bind" method="post" action="{{ url('/phone/bind') }}">
            {!! csrf_field() !!}
            <input name="username" type="text" value="" class="i-form account" placeholder="请输入您的手机号">
            <input name="password" type="password" value="" class="i-form pass" placeholder="请输入您的验证码">
            <button class="phone-btn" onclick="getCode()">获取验证码</button>

            <button type="submit" style="margin-top: 200px">确定</button>
        </form>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection