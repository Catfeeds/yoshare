@extends('themes.mobile.master')
@section('title', '绑定手机号-北京优享科技有限公司')

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
.account{
    background: url('../../images/index/account_bg.png') no-repeat 0 60px;
}
.pass{
    background: url('../../images/index/pass_bg.png') no-repeat 0 60px;
}
.phone-btn{
    top: 263px;
    right: 4%;
    color: #333;
    position: absolute;
    background: #fff;
    border: 1px solid #333;
    width: 30%;
    border-radius: 5px;
}
.back{
    margin-left: 0;
}
</style>
@endsection


@include('themes.mobile.members.header')

@section('content')
    <div class="wrapper">
        <div class="logo"><img src="{{url('images/logo.png')}}" alt="logo"></div>
        <div style="position: relative">
            <input name="mobile" id="mobile" type="text" value="" class="i-form account" placeholder="请输入您的手机号">
            <input name="code" id="code" type="text" value="" class="i-form pass" placeholder="请输入您的验证码">
            <button class="phone-btn" type="button" onclick="getCode()">获取验证码</button>

            <button type="submit" style="margin-top: 200px" onclick="bindMobile()">确定</button>
        </div>
    </div>
@endsection
@section('js')
<script src="{{ url('/js/layer.js') }}"></script>
<script>
    function getCode() {
        //类型0:找回密码,1:注册,2:重置密码,3:绑定手机,4:解除绑定手机
        var mobile = $("#mobile").val();
        $.ajax({
            url  : '/api/members/mobile/captcha',
            type : 'get',
            data : {
                'site_id' : 1,
                'mobile'  : mobile,
                'type'    : 3
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