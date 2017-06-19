<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title>全内容管理系统登录</title>
    <link href="/plugins/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
    <script src="/plugins/jquery/2.2.4/jquery.min.js"></script>
    <style type="text/css">
        body {
            background: url(/images/E_login_bg.jpg) no-repeat;
            background-size: cover;
            padding: 0;
            margin: 0;
            font-family: "微软雅黑", Microsoft YaHei, "宋体", Verdana, arial, sans-serif normal;
        }

        div {
            overflow: hidden;
            *display: inline-block;
        }

        div {
            *display: block;
        }

        .login_box {
            background: url(/images/E_login_box.png) no-repeat;
            width: 639px;
            height: 304px;
            overflow: hidden;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -320px;
            margin-top: -200px;
        }

        .login_iptbox {
            bottom: 12px;
            _bottom: 12px;
            color: #cfbbbb;
            font-size: 12px;
            height: 30px;
            left: 50%;
            margin-left: -300px;
            position: absolute;
            width: 600px;
            overflow: visible;
        }

        .login_iptbox .ipt {
            border-radius: 3px;
            padding: 0 8px;
            height: 24px;
            width: 100px;
            color: #252525;
            background: #cfbbbb;
            *line-height: 24px;
            border: none;
            overflow: hidden;
        }

        .login_iptbox label {
            *position: relative;
            *top: -6px;
        }

        .login_iptbox .input_captcha {
            margin-left: 5px;
            width: 55px;
            /*margin-right: 16px;*/
            overflow: hidden;
            text-align: left;
            padding: 2px 0 2px 5px;
            font-size: 16px;
            font-weight: bold;
        }

        .login_tj_btn {
            background: url(/images/E_login_btn.png) no-repeat 0px 0px;
            width: 89px;
            height: 28px;
            margin-left: 16px;
            border: none;
            cursor: pointer;
            padding: 0px;
            float: right;
        }

        .code_block {
            position: absolute;
            right: 60px;
            bottom: 70px
        }

        .error-block {
            font-weight: bold;
            color: #FF0004;
            font-size: 20px;
        }

        .captcha_area {
            position: absolute;
            background: url(/images/login_captcha.gif) no-repeat;
            width: 140px;
            height: 89px;
            right: 80px;
            top: -96px;
            text-align: center;
            font-size: 12px;
            display: none;
            outline: none;
        }

        .click_captcha:link, .click_captcha:visited {
            color: #036;
            text-decoration: none;
        }

        .click_captcha:hover {
            color: #C30;
        }

        .captcha_area img {
            cursor: pointer;
            margin: 4px auto 7px;
            margin-bottom: 3px;
            width: 130px;
            height: 50px;
            border: 1px solid #fff;
        }

        .click_captcha {
            color: #036;
            cursor: pointer;
        }

    </style>
</head>
<body class="pace-top">
<!-- begin #page-container -->
<div id="page-container" class="login_box">
    <!-- begin login -->
    <form role="form" method="POST" action="{{ url('admin/login') }}">
        <div class="login_iptbox">

            {{ csrf_field() }}

            <button type="submit" class="login_tj_btn">　</button>
            用户名：<input type="text" name="username"
                       value="{{ session('_old_input') ? session('_old_input.username') : ''}}" class="ipt"
                       placeholder=""/>
            @if($errors->has('username') || session('cancel'))
                <span class="error-block">*</span>
            @endif

            密 码：<input type="password" name="password"
                       value="{{ session('_old_input') ? session('_old_input.password') : ''}}" class="ipt"
                       placeholder=""/>
            @if($errors->has('password'))
                <span class="error-block">*</span>
            @endif

            验证码：<input type="text" name="captcha" class="form-control ipt input_captcha"
                       id="input_captcha" title="点击更换验证码" maxlength="5"
                       placeholder=""/>
            @if($errors->has('captcha'))
                <span class="error-block">*</span>
            @endif
            <div class="captcha_area" id="captcha_area" tabindex='3' onclick="refreshCaptcha('login_captcha')"
                 style="right: <?php echo $errors->has('username') ? '' : '112px'; ?>;">
                <img src="{{ captcha_src() }}" id="login_captcha" alt="验证码"
                     title="点击刷新图片">
                <span class="click_captcha">点击更换验证码</span>
            </div>
        </div>
    </form>
</div>
</body>
</html>

<script>
    function refreshCaptcha(id_name) {
        var input_captcha = $('#input_captcha').val();
        var img_src = "{{ url('auth/captcha') }}" + '?id_name=' + id_name + '&t=' + Math.random();
        $('#' + id_name).prop('src', img_src);
    }

    $('#input_captcha').on('focus', this, function () {
        $('#captcha_area').show();
    })
</script>