@extends('admin.layouts.master')
@section('content')
    <script src="{{ url('plugins/ace/1.2.7/ace.js') }}"></script>
    <script src="{{ url('plugins/ace/1.2.7/ext-language_tools.js') }}"></script>
    <script src="{{ url('plugins/ace/1.2.7/ext-emmet.js') }}"></script>
    <script src="{{ url('plugins/ace/1.2.7/emmet-core/emmet.js') }}"></script>
    <script src="{{ url('plugins/ace/1.2.7/theme-monokai.js') }}"></script>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                模板设置
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">模板设置</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-body">
                            @include('admin.layouts.flash')
                            @include('admin.templates.toolbar')
                            <div>
                                <pre id="editor" style="min-height:600px;">{{ $code }}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        var editor = ace.edit("editor");
        editor.session.setMode("ace/mode/php");
        editor.setTheme("ace/theme/monokai");
        editor.setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: false,
            enableLiveAutocompletion: false,
            enableEmmet: true
        });
    </script>
@endsection