@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                推送日志
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">推送日志</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-body">
                            @include('layouts.flash')
                            <table id="table" data-toggle="table" style="word-break:break-all;">
                                <thead>
                                <tr>
                                    <th data-field="state" data-checkbox="true"></th>
                                    <th data-field="id" data-width="30">ID</th>
                                    <th data-field="content_type" data-width="60" data-align="center">类型</th>
                                    <th data-field="content_title" data-formatter="titleFormatter">内容标题</th>
                                    <th data-field="send_no" data-width="90" data-align="center">send_no</th>
                                    <th data-field="msg_id" data-width="90" data-align="center">msg_id</th>
                                    <th data-field="username" data-width="60" data-align="center">操作员</th>
                                    <th data-field="state_name" data-width="60" data-formatter="stateFormatter" data-align="center">状态</th>
                                    <th data-field="created_at" data-width="130" data-align="center">推送时间</th>
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
        $('#table').bootstrapTable({
            method: 'get',
            url: '/push/log/table',
            pagination: true,
            pageNumber: 1,
            pageSize: 20,
            pageList: [10, 25, 50, 100],
            sidePagination: 'server',
            clickToSelect: true,
            striped: true,
        });

        function titleFormatter(value, row, index) {
            return [
                '<a href="/contents/' + row.content_id + '" target="_blank">' + value + '</a>',
            ]
        }

        function stateFormatter(value, row, index) {
            var style = 'label-primary';
            switch (row.state_name) {
                case '成功':
                    style = 'label-success';
                    break;
                case '失败':
                    style = 'label-danger';
                    break;
            }
            return [
                '<span class="label ' + style + '">' + value + '</span>',
            ].join('');
        }
    </script>
@endsection