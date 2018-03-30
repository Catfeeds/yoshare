@extends('themes.mobile.master')
@section('title', '重置密码-北京优享科技有限公司')

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
        <div class="logo" style="padding-top: 200px;"><img src="{{url('images/logo.png')}}" alt="logo"></div>
        <div style="position: relative">
            <input name="oldpass" id="oldpass" type="password" value="" class="i-form pass"  placeholder="请输入您的原密码">
            <input name="password" id="password" type="password" value="" class="i-form pass"  placeholder="请输入您的新密码">
            <input name="password2" id="password2" type="password" value="" class="i-form pass" placeholder="请确认您的新密码">

            <button type="button" style="margin-top: 200px" id="reset">确定</button>
        </div>
    </div>
@endsection
@section('js')
<script src="{{ url('/js/layer.js') }}"></script>
<script>
    $('#reset').click(function () {
        var oldpass = $("#oldpass").val();
        var password = $("#password").val();
        var password2 = $("#password2").val();
        if(oldpass == ''){
            layer.open({
                content: '您输入旧密码'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }
        if(password == ''){
            layer.open({
                content: '您输入新密码'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }
        if(password !== password2){
            layer.open({
                content: '您输入的密码不一致，请重新输入'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }

        $.ajax({
            url  : '/password/reset/',
            type : 'post',
            data : {
                oldpass    : oldpass,
                password   : password,
                password2  : password2,
                _token     : '{{ csrf_token() }}'
            },
            success:function(data){
                msg = data.message;
                statusCode = data.status_code;
                if(statusCode == '200'){
                    layer.open({
                        content: '密码修改成功，用新密码登录'
                        ,btn: ['确定', '取消']
                        ,yes: function(index){
                            location.href = '/login';
                            layer.close(index);
                        }
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
    })

</script>
@endsection