@extends('themes.mobile.master')
@section('title', '验证手机号-游享')

@section('css')
    <link href="{{ url('css/member.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/cart.css') }}" type="text/css" rel="stylesheet">
<style>
body{
    background: #fff;
}
.i-form{
    height: 210px;
}
.phone{
    background: url('../../images/index/phone_bg.png') no-repeat 20px 76px;
}
.code{
    background: url('../../images/index/code_bg.png') no-repeat 9px 70px;
}
.phone-btn{
    top: 263px;
    right: 4%;
    position: absolute;
    background: #fff;
    border: 1px solid #333;
    width: 30%;
    border-radius: 5px;
    height: 118px;
    font-size: 40px;
    text-align: center;
}
.back{
    margin-left: 0;
}
</style>
@endsection


@include('themes.mobile.members.header')

@section('content')
    <div class="wrapper">
        <div class="logo" style="padding-top: 200px;"><img src="{{url('images/logo.png')}}" alt="logo"></div>
        <div style="position: relative">
            <input style="color: #333;" name="mobile" id="mobile" type="text" value="{{ $member['mobile'] }}" @if(empty($member['mobile']))  placeholder="您还未绑定手机，请绑定手机后操作" @endif disabled="disabled" class="i-form phone">
            <input name="code" id="code" type="text" value="" class="i-form code" placeholder="请输入您的验证码">
            <input class="phone-btn" type="button" onclick="getCode(this)" value="获取验证码"/>

            <button type="submit" style="margin-top: 200px" onclick="verify()">下一步</button>
        </div>
    </div>
@endsection
@section('js')
<script src="{{ url('/js/layer.js') }}"></script>
<script>

    var countdown=60;

    function settime(obj) {

        if (countdown == 0) {
            obj.removeAttribute("disabled");
            obj.value="获取验证码";
            countdown = 60;
        }else {
            obj.setAttribute("disabled", true);
            obj.value="重新发送(" + countdown + ")";
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
                content: '请先绑定手机'
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

    function verify() {

        var mobile  = $("#mobile").val();
        var captcha = $("#code").val();

        $.ajax({
            url  : '/password/forget/verify',
            type : 'get',
            data : {
                'captcha' : captcha,
                'mobile'  : mobile
            },
            success:function(data){
                msg = data.message;
                statusCode = data.status_code;
                if(statusCode == '200'){
                    location.href = '/password/reset';
                } else if(statusCode == '401') {
                    layer.open({
                        content: msg,
                        btn: ['确认', '取消'],
                        yes: function(index, layero) {
                            window.location.href = '/login';
                        }
                    });
                } else{
                    layer.open({
                        content: msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            }
        })
    }

</script>
@endsection