@extends('admin.layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                内容管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">内容管理</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-2">
                    <div class="box box-success">
                        <div class="box-body">
                            <div id="tree"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="box box-info">
                        <div class="box-body">
                            @include('admin.layouts.confirm', ['message' => '您确认删除该条信息吗？'])
                            @include('admin.layouts.flash')
                            @include('admin.contents.toolbar')
                            @include('admin.contents.copy')
                            @include('admin.contents.query')
                            @include('admin.layouts.modal', ['id' => 'modal_comment'])
                            @include('admin.layouts.push')
                            @include('admin.contents.table')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        var index = 0;

        /* 获取栏目树 */
        $.getJSON('/admin/contents/categories', function (data) {
            $('#tree').treeview({
                data: data,
                searchResultColor: 'white',
                levels: 4,
                onNodeSelected: function (event, data) {
                    category_id = data.id;
                    $('#table').bootstrapTable('refresh');
                }
            });

            if (getQueryString('category_id') == null) {
                $('#tree').treeview('selectNode', [0, {silent: false}]);
            }
            else {
                if (getNodeId(parseInt(getQueryString('category_id')), data) >= 0) {
                    $('#tree').treeview('selectNode', [index, {silent: false}]);
                }
            }
        });
    </script>
@endsection