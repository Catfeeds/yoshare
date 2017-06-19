@extends('layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                文件管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">文件管理</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-body">
                            <input id="input-24" name="input24[]" type="file" multiple class="file-loading">
                            <button class="btn btn-block btn-success btn-xs" id="submit">Success</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        $(document).on('ready', function () {
            $("#input-24").fileinput({
                language: 'zh',
                initialPreview: [
                    '<input id="title1" type="text" class="form-control input-sm image-title" placeholder="请输入标题"><img src="http://cms.asia-cloud.com/uploads/images/2016/0909/20160909103653344.jpg" class="kv-preview-data file-preview-image" style="height:160px"><textarea class="form-control input-sm image-content" placeholder="请输入详细内容"></textarea>',
                    '<input id="title2" type="text" class="form-control input-sm image-title" placeholder="请输入标题"><img src="http://cms.asia-cloud.com/uploads/images/2016/0909/20160909103654849.jpg" class="kv-preview-data file-preview-image" style="height:160px"><textarea class="form-control input-sm image-content" placeholder="请输入详细内容"></textarea>',
                ],
                initialPreviewAsData: false,
                initialPreviewConfig: [
                    {key: 1},
                    {key: 2},
                ],
                deleteUrl: "/site/file-delete",
                overwriteInitial: false,
                maxFileSize: 100,
                initialCaption: "The Moon and the Earth"
            });
            $('#submit').click(function () {
                alert($('#title1').val());
            });
            $('#input-24').on('filezoomshow', function(event, params) {
                $('.image-title').hide();
                $('.image-content').hide();
            });
            $('#input-24').on('filezoomhide', function(event, params) {
                $('.image-title').show();
                $('.image-content').show();
            });
        });
    </script>
@endsection