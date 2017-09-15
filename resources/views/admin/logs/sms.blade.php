@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                短信日志
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">短信日志</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-body">
                            @include('admin.layouts.flash')
                            @include('admin.logs.toolbar')
                            @include('admin.logs.query')
                            <table id="table" data-toggle="table" style="word-break:break-all;">
                                <thead>
                                <tr>
                                    <th data-field="state" data-checkbox="true"></th>
                                    <th data-field="id" data-width="30">ID</th>
                                    <th data-field="site_title" data-width="60" data-align="center">站点</th>
                                    <th data-field="mobile" data-width="90" data-align="center">手机号</th>
                                    <th data-field="message">信息</th>
                                    <th data-field="state_name" data-width="60" data-align="center" data-formatter="stateFormatter">状态</th>
                                    <th data-field="created_at" data-width="150" data-align="center">发送时间</th>
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
            url: '/admin/sms/log/table',
            pagination: true,
            pageNumber: 1,
            pageSize: 20,
            pageList: [10, 25, 50, 100],
            sidePagination: 'server',
            clickToSelect: true,
            striped: true,
            queryParams: function (params) {
                var object = $('#form_query input,#form_query select').serializeObject();
                object['state'] = $('#state').val();
                object['_token'] = '{{ csrf_token() }}';
                object['offset'] = params.offset;
                object['limit'] = params.limit;
                return object;
            },
        });

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