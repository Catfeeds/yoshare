@extends('admin.layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                模型管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">模型管理</li>
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
                            @include('admin.layouts.modal', ['id' => 'modal_create'])
                            <div id="toolbar" class="btn-group">
                                <button class="btn btn-primary btn-sm margin-r-5 create">新增模型</button>
                                <button class="btn btn-primary btn-sm margin-r-5 edit">编辑模型</button>
                            </div>

                            <table id="table"
                                   data-toggle="table"
                                   data-url="/admin/modules/table/{{ $module_id }}"
                                   data-pagination="false"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-show-toggle="true"
                                   data-show-columns="true"
                                   data-toolbar="#toolbar">
                                <thead>
                                <tr>
                                    <th data-field="sort" data-align="center" data-width="45" data-editable="true">序号</th>
                                    <th data-field="name" data-width="90">字段名</th>
                                    <th data-field="alias" data-width="90">别名</th>
                                    <th data-field="type" data-align="center" data-width="90">类型</th>
                                    <th data-field="sort" data-align="center"  data-width="60">长度</th>
                                    <th data-field="system" data-align="center" data-width="45">系统</th>
                                    <th data-field="required" data-align="center" data-width="45">必填</th>
                                    <th data-field="action" data-align="center" data-width="70" data-formatter="actionFormatter" data-events="actionEvents"> 操作
                                    </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <script>

        $.getJSON('/admin/modules/tree', function (data) {
            $('#tree').treeview({
                data: data,
                searchResultColor: 'white',
                levels: 3,
                onNodeSelected: function (event, data) {
                    location.href = '/admin/modules?module_id=' + data.id;
                }
            });
        });

        $('#table').bootstrapTable({
            onEditableSave: function (field, row, old, $el) {
                $.ajax({
                    url: "/admin/modules/" + row.id + '/save',
                    data: {'_token': '{{ csrf_token() }}', 'sort': row.sort},
                    success: function (data, status) {
                    },
                    error: function (data) {
                        alert('Error');
                    },
                });
            },
        });

        $(".create").click(function () {
            window.location.href = '/admin/modules/create';
        });

        $(".edit").click(function () {
            window.location.href = '/admin/modules/1/edit';
        });

        $('#table').on('all.bs.table', function (e, name, args) {
            $(window).resize();
        });

        function actionFormatter(value, row, index) {
            return [
                '<button class="btn btn-primary btn-xs edit" data-toggle="modal" data-target="#modal_edit">' +
                '<i class="fa fa-edit" data-toggle="tooltip" data-placement="left" title="编辑" ></i></button>',
                '<span> </span>',
                '<button class="btn btn-danger btn-xs remove" data-toggle="modal" data-target="#modal"><i class="fa fa-trash"></i></button>'
            ].join('');
        }

        $("#modal_remove").click(function () {
            var row_id = $(this).data('id');
            $.ajax({
                url: 'admin/modules/' + row_id + '/delete',
                data: {'_token': '{{ csrf_token() }}'},
                success: function (data) {
                    window.location.href = '/admin/modules';
                }
            });
        });

        window.actionEvents = {
            'click .edit': function (e, value, row, index) {
                window.location.href = '/admin/modules/' + row.id + '/edit';
            },
            'click .remove': function (e, value, row, index) {
                $('#modal_remove').data('id', row.id);
            },
        };

        function stateFormatter(value, row, index) {
            var style = 'label-primary';
            switch (row.state_name) {
                case '已启用':
                    style = 'label-success';
                    break;
                case '已禁用':
                    style = 'label-danger';
                    break;
            }
            return [
                '<span class="label ' + style + '">' + row.state_name + '</span>',
            ].join('');
        }
    </script>
@endsection