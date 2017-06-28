@extends('admin.layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                字典设置
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li><a href="#">媒资管理系统</a></li>
                <li class="active">字典设置</li>
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
                                        onclick="javascript:window.location.href='/admin/dictionaries/create'">新增
                                </button>
                            </div>

                            <table data-toggle="table"
                                   data-url="dictionaries/table"
                                   data-pagination="true"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-show-toggle="true"
                                   data-show-columns="true"
                                   data-toolbar="#toolbar">
                                <thead>
                                <tr>
                                    <th data-field="code">编码</th>
                                    <th data-field="name">名称</th>
                                    <th data-field="value">值</th>
                                    <th data-field="site_id">站点</th>
                                    <th data-field="action" data-formatter="actionFormatter" data-events="actionEvents">操作</th>
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
            $.ajax({
                type:'get',
                url:'/admin/dictionaries/'+row_id+'/delete',
                success:function(data){
                    window.location.href = '/admin/dictionaries';
                }
            });
        });

        window.actionEvents = {
            'click .like': function (e, value, row, index) {

                alert('You click like icon, row: ' + JSON.stringify(row));
                console.log(value, row, index);
            },
            'click .edit': function (e, value, row, index) {
                window.location.href = '/admin/dictionaries/' + row.id + '/edit';
            },
            'click .remove': function (e, value, row, index) {
                $('#modal_remove').data('id', row.id);
            },
        };


    </script>

@endsection