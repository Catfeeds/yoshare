@extends('layouts.master')

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
                            @include('layouts.confirm', ['message' => '您确认删除该条信息吗？'])
                            @include('layouts.flash')
                            @include('contents.toolbar')
                            @include('contents.copy')
                            @include('layouts.modal', ['id' => 'modal_comment'])
                            @include('push.modal')
                            @include('contents.table')
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <script>
        var index = 0;

        function getQueryString(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]);
            return null;
        }

        $.getJSON('/contents/categories', function (data) {
            $('#tree').treeview({
                data: data,
                searchResultColor: 'white',
                levels: 1,
                onNodeSelected: function (event, data) {
                    category_id = data.id;
                    $('#table').bootstrapTable('selectPage', 1);
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

        function getNodeId(id, data) {
            for (var i = 0; i < data.length; i++) {
                if (data[i].id == id) {
                    return i;
                }
                index++;
                if (data[i].nodes != null && data[i].nodes.length > 0) {
                    var ret = getNodeId(id, data[i].nodes);
                    if (ret >= 0) {
                        return ret;
                    }
                }
            }
            return -1;
        }

        $('#contents_query').click(function () {
            $('#table').bootstrapTable('selectPage', 1);
        });
    </script>
@endsection