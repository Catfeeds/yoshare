@extends('themes.mobile.master')
@section('title', '系统设置-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/set.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')

    @include('themes.mobile.layouts.header')

    <div class="u-wrapper">
        <ul class="set">
            <li onclick="jump('/member/detail')">
                <div class="avatar">
                    <img src="{{ $member->avatar }}" alt="avatar">
                </div>
                <div class="name">
                    <p>用户名：{{ $member->username }}</p>
                    <p>性　别：{{ \App\Models\Member::SEX[$member->sex] }}</p>
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