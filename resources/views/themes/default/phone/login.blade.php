@extends('templates.master')
@section('title', '登录-北京优享科技有限公司')
@section('content')
    <div class="wrapper">
        <div class="logo"><img src="{{url('images/logo.png')}}" alt="logo"></div>
        <form class="forms" name="login" id="login" method="post" action="{{ url('/user/add') }}"
              onsubmit="return CheckRegister(document.register);">
            {!! csrf_field() !!}
            <input name="username" type="text" value="" class="i-form account" placeholder="请输入您的手机号">
            <input name="password" type="password" value="" class="i-form pass" placeholder="请输入您的验证码">
            <button class="phone-btn" onclick="getCode()">获取验证码</button>
            <a href="{{url('/login')}}" alt="登录" class="homehref">账号登录</a>
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
    if (self != top) {
        parent.location.href = 'index.php';
    }
    function CheckRegister(obj) {
        if (obj.username.value == '') {
            alert('请输入用户名');
            obj.username.focus();
            return false;
        }
        if (obj.password.value == '') {
            alert('请输入登录密码');
            obj.password.focus();
            return false;
        }
        return true;
    }
</script>
@endsection