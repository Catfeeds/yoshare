@extends('themes.mobile.master')
@section('title', '系统设置-游享')
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
                    @if($member->avatar_url == 0)
                        <img src={{ url('images/avatar/boy.png') }} alt="avatar">
                    @else
                        <img src={{ url('images/avatar/girl.png') }} alt="avatar">
                    @endif
                </div>
                <div class="name">
                    <p>用户名：{{ $member->username }}</p>
                    <p>性　别：{{ \App\Models\Member::SEX[$member->sex] }}</p>
                </div>
                <div class="clear"></div>
            </li>
            <li onclick="jump('/member/verify')">修改密码</li>
            <li onclick="jump('/help')">帮助与反馈</li>
            <li onclick="jump('/pages/about.html')">关于我们</li>
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