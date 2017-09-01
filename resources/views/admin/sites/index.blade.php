@extends('admin.layouts.master')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                站点管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">站点管理</li>
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
                                <button class="btn btn-primary btn-xs margin-r-5 margin-b-5" id="create"
                                        onclick="javascript:window.location.href='/admin/sites/create'">新增
                                </button>
                            </div>

                            <table data-toggle="table"
                                   data-url="sites/table"
                                   data-toolbar="#toolbar">
                                <thead>
                                <tr>
                                    <th data-field="id">ID</th>
                                    <th data-field="name">英文名称</th>
                                    <th data-field="title">标题</th>
                                    <th data-field="directory">目录</th>
                                    <th data-field="domain">域名</th>
                                    <th data-field="updated_at" data-align="center" data-width="120">更新时间</th>
                                    <th data-field="action" data-formatter="actionFormatter" data-events="actionEvents" data-align="center" data-width="100">操作</th>
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
            ].join('');
        }

        $("#modal_remove").click(function () {
            var row_id = $(this).data('id');

            $.ajax({
                type:'get',
                data: {'_token': '{{ csrf_token() }}'},
                url:'/admin/sites/'+row_id+'/delete',
                success:function(data){
                    window.location.href = '/admin/sites';
                }
            });
        });

        window.actionEvents = {
            'click .like': function (e, value, row, index) {

                alert('You click like icon, row: ' + JSON.stringify(row));
                console.log(value, row, index);
            },
            'click .edit': function (e, value, row, index) {
                window.location.href = '/admin/sites/' + row.id + '/edit';
            },
            'click .remove': function (e, value, row, index) {
                $('#modal_remove').data('id', row.id);
            },
        };

    </script>

@endsection