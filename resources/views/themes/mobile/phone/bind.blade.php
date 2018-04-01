@extends('themes.mobile.master')
@section('title', '绑定手机号-游享')

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
}
.back{
    margin-left: 0;
}
</style>
@endsection


@include('themes.mobile.members.header')

@section('content')
    <div class="wrapper">
        <div class="logo" style="padding-top: 145px;"><img src="{{url('images/logo.png')}}" alt="logo"></div>
        <div style="position: relative">
            @if(!empty($member['mobile']))
                <input name="mobile" type="text" style="color: #333;" value="{{ $member['mobile'] }}" disabled="disabled" class="i-form phone">
            @endif
            <input name="mobile" id="mobile" type="text" value="" class="i-form phone" @if(!empty($member['mobile'])) placeholder="请输入您要换绑的手机号"  @else  placeholder="请输入您的手机号" @endif>
            <input name="code" id="code" type="text" value="" class="i-form code" placeholder="请输入您的验证码">
            <input class="phone-btn" type="button" onclick="getCode(this)" value="获取验证码"/>

            <button type="submit" style="margin-top: 120px" onclick="bindMobile()">确定</button>
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
        //类型0:找回密码,1:注册,2:重置密码,3:绑定手机,4:解除绑定手机
        $.ajax({
            url  : '/api/members/mobile/captcha',
            type : 'get',
            data : {
                'site_id' : 1,
                'mobile'  : mobile,
                'type'    : {{ \App\Models\Member::CAPTCHA_BIND }}
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

    function bindMobile() {
        var mobile  = $("#mobile").val();
        var captcha = $("#code").val();
        $.ajax({
            url  : '/member/bind/phone',
            type : 'get',
            data : {
                'captcha' : captcha,
                'mobile'  : mobile
            },
            success:function(data){
                msg = data.message;
                if(msg == 'success'){
                    layer.open({
                        content: '绑定成功！'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                } else {
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