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
                主题管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">主题管理</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-3">
                    <div class="box box-success">
                        <div class="box-body">
                            <div id="tree">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="box box-info">
                        <div class="box-body">
                            @include('admin.layouts.flash')
                            <ul id="tabs" class="nav nav-tabs">
                                <li class="active">
                                    <a href="#" data-toggle="tab"></i>
                                        <span class="label label-primary">default</span>
                                        <span class="label label-default">/</span>
                                        <span class="label label-primary">articles</span>
                                        <span class="label label-default">/</span>
                                        <span class="label label-primary margin-r-5">list.blade.php</span>
                                        <button class="btn btn-info btn-xs"><i class="fa fa-save"></i></button>
                                    </a>
                                </li>
                                <li class="pull-right">
                                    <button class="btn btn-success btn-xs margin-r-5 margin-t-5" id="btn_create">添加文件</button>
                                    <button class="btn btn-danger btn-xs margin-r-5 margin-t-5" id="btn_remove">删除文件</button>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-xs margin-t-5">变量列表</button>
                                        <button type="button" class="btn btn-info btn-xs margin-r-5 margin-t-5 dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">站点</a></li>
                                            <li><a href="#">站点-名称</a></li>
                                            <li><a href="#">站点-单位名称</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">文章</a></li>
                                            <li><a href="#">文章-标题</a></li>
                                            <li><a href="#">文章-内容</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-xs margin-t-5">代码片段</button>
                                        <button type="button" class="btn btn-info btn-xs margin-r-5 margin-t-5 dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">继承模板</a></li>
                                            <li><a href="#">显示数据</a></li>
                                            <li><a href="#">显示原生数据</a></li>
                                            <li><a href="#">循环语句</a></li>
                                            <li><a href="#">判断语句</a></li>
                                            <li><a href="#">包含子视图</a></li>
                                            <li class="divider"></li>
                                            <li><a href="http://laravelacademy.org/post/5919.html" target="_blank">参考文档</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                            <div id="tabs" class="tab-content">
                                <div id="list" class="tab-pane fade in active padding-t-15">
                                    <pre id="editor" style="min-height:600px;"></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    @include('admin.themes.script')
@endsection