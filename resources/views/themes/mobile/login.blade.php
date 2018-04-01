@extends('themes.mobile.master')
@section('title', '登录-游享')

@section('css')
    <link href="{{ url('css/home.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="wrapper">
        <div class="logo"><img src="{{url('images/logo.png')}}" alt="logo"></div>
        <form name="login" id="login" method="post" action="{{ url('/user/add') }}"
              onsubmit="return CheckRegister(document.register);">
            {!! csrf_field() !!}
            <input name="username" type="text" value="" class="i-form account" placeholder="用户名">
            <input name="password" type="password" value="" class="i-form pass" placeholder="密码">
            <a href="{{url('/phone/login')}}" alt="登录" class="homehref">手机验证码登录</a>
            <button type="submit">登录</button>
            <div class="register-a"><a href="/register">一一一一一　　　注册账号　　　一一一一一</a></div>
            <ul class="third-party clear">
                <li><img src="{{url('images/index/alipay_logo.png')}}" alt="alipay"></li>
                <li><img src="{{url('images/index/wechat_logo.png')}}" alt="wechat"></li>
            </ul>
        </form>
    </div>
@endsection
@section('js')
    <script>

    </script>
@endsection