@extends('themes.mobile.master')
@section('title', '注册-北京优享科技有限公司')

@section('css')
    <link href="{{ url('css/home.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="wrapper">
        <div class="logo"><img src="{{url('images/logo.png')}}" alt="logo"></div>
        <form name="register" id="register" method="post" action="{{ url('/user/add') }}"
              onsubmit="return CheckRegister(document.register);">
            {!! csrf_field() !!}
            <input name="username" type="text" value="" class="i-form" placeholder="输入您的用户名">
            <input name="password" type="password" value="" class="i-form" placeholder="输入您的密码">
            <input name="repass" type="password" value="" class="i-form" placeholder="请再次确认密码">
            <a class="homehref" href="{{url('/login')}}" alt="登录">已有账号，直接登录</a>
            <button type="submit">注册</button>
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
            if (obj.password.value != obj.repass.value) {
                alert('两次输入密码不一致，请重新弄输入');
                obj.password.value = obj.repass.value = ''；
                obj.password.focus();
                return false;
            }
            return true;
        }
    </script>
@endsection