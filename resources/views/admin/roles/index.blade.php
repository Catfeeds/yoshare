@extends('admin.layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                角色管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">角色管理</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-body">
                            @include('admin.layouts.flash')
                            @include('admin.layouts.confirm', ['message' => '您确认删除该条信息吗？'])
                            <div id="toolbar" class="btn-group">
                                <button class="btn btn-primary btn-sm margin-r-5" id="create"
                                        onclick="javascript:window.location.href='/admin/roles/create'">新增
                                </button>
                            </div>

                            <table data-toggle="table"
                                   data-url="roles/table"
                                   data-pagination="true"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-show-toggle="true"
                                   data-show-columns="true"
                                   data-toolbar="#toolbar">
                                <thead>
                                <tr>
                                    <th data-field="id" data-width="30">ID</th>
                                    <th data-field="name" data-width="120">角色名称</th>
                                    <th data-field="description">描述</th>
                                    <th data-field="created_at" data-width="120">创建时间</th>
                                    <th data-field="updated_at" data-width="120">更新时间</th>
                                    <th data-field="action" data-formatter="actionFormatter" data-events="actionEvents"
                                        data-width="100">操作
                                    </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>
    <script>
        function actionFormatter(value, row, index) {
            return [
                '<a class="edit" href="javascript:void(0)"><button class="btn btn-primary btn-xs">编辑</button></a>',
                '<span> </span>',
                '<a class="remove" href="javascript:void(0)"><button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal">删除</button></a>',
            ].join('');
        }

        $("#modal_remove").click(function () {
            var row_id = $(this).data('id');
            var token = document.getElementsByTagName('meta')['csrf-token'].getAttribute('content');
            $.ajax({
                url: '/admin/roles/' + row_id + '/delete',
                data: {'_token': token},
                success: function (data) {
                    window.location.href = '/admin/roles';
                }
            });
        });

        window.actionEvents = {
            'click .edit': function (e, value, row, index) {
                window.location.href = '/admin/roles/' + row.id + '/edit';
            },

            'click .remove': function (e, value, row, index) {
                $('#modal_remove').data('id', row.id);
            },
        };
    </script>
@endsection