@extends('layouts.master')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                应用管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">应用管理</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-body">
                            @include('layouts.flash')
                            @include('layouts.confirm', ['message' => '您确认删除该条信息吗？'])
                            <div id="toolbar" class="btn-group">
                                <button class="btn btn-primary btn-sm margin-r-5" id="create"
                                        onclick="javascript:window.location.href='/apps/create'">新增
                                </button>
                            </div>

                            <table data-toggle="table"
                                   data-url="apps/table"
                                   data-pagination="true"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-show-toggle="true"
                                   data-show-columns="true"
                                   data-toolbar="#toolbar">
                                <thead>
                                <tr>
                                    <th data-field="id">ID</th>
                                    <th data-field="name">名称</th>
                                    <th data-field="android_version">安卓版本号</th>
                                    <th data-field="android_force" data-width="60" data-formatter="androidForceFormatter">安卓强制更新</th>
                                    <th data-field="ios_version">ios版本号</th>
                                    <th data-field="ios_force" data-width="60" data-formatter="iosForceFormatter">ios强制更新</th>
                                    <th data-field="created_at" data-width="120">创建时间</th>
                                    <th data-field="updated_at" data-width="120">更新时间</th>
                                    <th data-field="action" data-formatter="actionFormatter" data-events="actionEvents" data-width="100">操作</th>
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
        function androidForceFormatter(value, row, index){
            var style = 'label-primary';
            var html = '';
            if(row.android_force != false){
                style = 'label-success';
                html = '是';
            }0
            return [
                '<span class="label ' + style + '">' + html + '</span>',
            ].join('');
        }

        function iosForceFormatter(value, row, index){
            var style = 'label-primary';
            var html = '';
            if(row.ios_force != false){
                style = 'label-success';
                html = '是';
            }
            return [
                '<span class="label ' + style + '">' + html + '</span>',
            ].join('');
        }

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
                url:'/apps/'+row_id+'/delete',
                success:function(data){
                    window.location.href = '/apps';
                }
            });
        });

        window.actionEvents = {
            'click .like': function (e, value, row, index) {

                alert('You click like icon, row: ' + JSON.stringify(row));
                console.log(value, row, index);
            },
            'click .edit': function (e, value, row, index) {
                window.location.href = '/apps/' + row.id + '/edit';
            },
            'click .remove': function (e, value, row, index) {
                $('#modal_remove').data('id', row.id);
            },
        };

    </script>

@endsection