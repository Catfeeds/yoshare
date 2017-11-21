@extends('themes.mobile.master')
@section('title', '系统设置-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/set.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="u-wrapper">

        @include('themes.mobile.layouts.header')

        <ul class="set">
            <li onclick="">
                <div class="avatar">
                    <img src="" alt="avatar">
                </div>
                <div class="name">
                    <p>Yo-jd-4457c96a97bc8</p>
                    <p>用户名：Yo-jd-4457c96a97bc8</p>
                </div>
                <div class="clear"></div>
            </li>
            <li onclick="jump('password/reset')">密码修改</li>
            <li onclick="">帮助与反馈</li>
            <li onclick="jump('/about/us')">关于我们</li>
            <li onclick="exit()">退出登录</li>
        </ul>

    </div>
@endsection
@section('js')
<script src="{{ url('/js/layer.js') }}"></script>
<script>
    function exit() {
        layer.open({
            content: '您确定要退出登录吗？'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                location.href = '/logout';
                layer.close(index);
            }
        });
    }
</script>
@endsection