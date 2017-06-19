<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>内容管理系统</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!--JQuery-->
    <script src="/plugins/jquery/2.2.4/jquery.min.js"></script>

    <!--Bootstrap-->
    <link href="/plugins/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="/plugins/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Font Awesome -->
    <link href="/plugins/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">

    <!-- Theme style -->
    <link rel="stylesheet" href="/plugins/admin-lte/2.3.7/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/plugins/admin-lte/2.3.7/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!--Bootstrap-Table-->
    <link href="/plugins/bootstrap-table/1.11.0/bootstrap-table.min.css" rel="stylesheet">
    <script src="/plugins/bootstrap-table/1.11.0/bootstrap-table.min.js"></script>
    <script src="/plugins/bootstrap-table/1.11.0/extensions/editable/bootstrap-table-editable.min.js"></script>
    <script src="/plugins/bootstrap-table/1.11.0/locale/bootstrap-table-zh-CN.min.js"></script>

    <!--Moment.js-->
    <script src="/plugins/moment.js/2.15.1/moment.min.js"></script>
    <script src="/plugins/moment.js/2.15.1/moment-with-locales.min.js"></script>
    <script src="/plugins/moment.js/2.15.1/locales.js"></script>

    <!--CKEditor-->
    <script src="/plugins/ckeditor/4.5.11/ckeditor.js"></script>

    <!--Bootstrap-DatetimePicker-->
    <link href="/plugins/bootstrap-datetimepicker/4.17.42/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <script src="/plugins/bootstrap-datetimepicker/4.17.42/js/bootstrap-datetimepicker.min.js"></script>

    <!--X-editable-->
    <link href="/plugins/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
    <script src="/plugins/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

    <!--Bootstrap-TreeView-->
    <link href="/plugins/bootstrap-treeview/1.2.0/bootstrap-treeview.min.css" rel="stylesheet">
    <script src="/plugins/bootstrap-treeview/1.2.0/bootstrap-treeview.min.js"></script>

    <!--Bootstrap-fileinput-->
    <link href="/plugins/bootstrap-fileinput/4.3.9/css/fileinput.min.css" rel="stylesheet">
    <script src="/plugins/bootstrap-fileinput/4.3.9/js/plugins/canvas-to-blob.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/4.3.9/js/plugins/sortable.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/4.3.9/js/plugins/purify.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/4.3.9/js/fileinput.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/4.3.9/js/locales/zh.js"></script>

    <!--Toastr.js-->
    <script src="/plugins/toastr.js/2.1.3/toastr.min.js"></script>
    <link href="/plugins/toastr.js/2.1.3/toastr.min.css" rel="stylesheet">

    <!--JqueryUI custom-->
    <script src="/plugins/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!--Select2-->
    <link href="/plugins/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="/plugins/select2/4.0.3/js/select2.full.min.js"></script>

    <!--Echarts-->
    <script src="/plugins/echarts/3.3.2/echarts.min.js"></script>

    <link href="/css/app.css" rel="stylesheet">
    <script src="/js/app.js"></script>
</head>

@yield('body')

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script type="text/javascript">
    $('div.alert').not('.alert-danger').delay(3000).slideUp(300);
</script>

<!-- AdminLTE App -->
<script src="/plugins/admin-lte/2.3.7/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/plugins/admin-lte/2.3.7/js/demo.js"></script>
</html>
