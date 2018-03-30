@extends('themes.mobile.master')
@section('title', '登录-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/home.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="wrapper">
        <div class="logo"><img src="{{url('images/logo.png')}}" alt="logo"></div>
        <form class="forms" name="login" id="login" method="POST" action="{{ url('/login') }}">
            {!! csrf_field() !!}
            <input name="mobile" type="text" value="" class="i-form account" placeholder="请输入您的手机号">
            <input name="code" type="text" value="" class="i-form pass" placeholder="请输入您的验证码">
            <button class="phone-btn" onclick="getCode(this)">获取验证码</button>
            <a href="{{url('/login')}}" alt="登录" class="homehref">账号登录</a>
            <button type="submit">登录</button>
            <div class="register-a"><a href="/register">一一一一一　　　注册账号　　　一一一一一</a></div>
            <ul class="third-party clear" style="display: none">
                <li><img src="{{url('images/index/alipay_logo.png')}}" alt="alipay"></li>
                <li><img src="{{url('images/index/wechat_logo.png')}}" alt="wechat"></li>
            </ul>
        </form>
    </div>
@endsection
@section('js')
<script>
    var countdown=60;

    function settime(obj) {

        if (countdown == 0) {
            obj.removeAttribute("disabled");
            obj.text="获取验证码";
            countdown = 60;
        }else {
            obj.setAttribute("disabled", true);
            obj.text="重新发送(" + countdown + ")";
            countdown--;
        }
        setTimeout(function() {
                settime(obj) }
            ,1000)
    }

    function getCode(obj) {
        var mobile = $("#mobile").val();
        if(mobile == ''){
            layer.open({
                content: '请输入手机号'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }else if(mobile.length !== 11){
            layer.open({
                content: '请输入正确的手机号'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }
        settime(obj);
        //类型 0:找回密码,1:注册,2:重置密码,3:绑定手机,4:解除绑定手机
        $.ajax({
            url  : '/api/members/mobile/captcha',
            type : 'get',
            data : {
                'site_id' : 1,
                'mobile'  : mobile,
                'type'    : {{ \App\Models\Member::CAPTCHA_RESET }}
            },
            success:function(data){
                msg = data.message;
                if(msg == 'success')
                    layer.open({
                        content: '发送成功，请查收'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
            }
        })
    }
</script>
@endsection