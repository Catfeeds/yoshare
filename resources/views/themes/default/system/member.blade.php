@extends('themes.mobile.master')
@section('title', '系统设置-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/set.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">

    <style>
        ul li{
            background: #fff;
        }
        ul li:first-child{
            background: #fff;
        }
    </style>
@endsection
@section('content')
    <div class="u-wrapper">

        @include('themes.mobile.layouts.header')
        {!! Form::model($member, ['id' => 'form', 'method' => 'PATCH', 'action' => ['MemberController@update', $member->id],'class' => 'form-horizontal']) !!}
        <ul class="set">
            <li onclick="" style="height: 220px;">
                <span style="line-height: 220px;">头　　像：</span>
                <div>
                    {!! Form::select('avatar_url', $member->avatarOptions, isset($member) ? $member->avatar_url: '', ['class' => 'a-form', 'style'=>'margin-top: 50px;']) !!}
                </div>
                <div class="clear"></div>
            </li>
            <li><span>用 户 名：</span>{!! Form::text('username', null, ['class' => 'a-form']) !!}</li>
            <li><span>性　　别：</span>{!! Form::select('sex', $member->sexOptions, isset($member) ? $member->sex: '', ['class' => 'a-form']) !!}</li>
            <li><span>邮　　箱：</span>{!! Form::text('email', null, ['class' => 'a-form']) !!}</li>
            <li><span>手机号码：</span>{!! Form::text('mobile', null, ['class' => 'a-form']) !!}</li>
            <li style="background: #ffcc42"><button type="submit">保存</button></li>
        </ul>
        {!! Form::close() !!}
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