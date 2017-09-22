@extends('admin.layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                问卷管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">问卷管理</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-body">
                            @include('admin.layouts.flash')
                            @include('admin.layouts.confirm', ['message' => '您确认删除该条信息吗？'])
                            @include('admin.surveys.toolbar')
                            @include('admin.layouts.modal', ['id' => 'modal_count'])
                            <table id="table" data-toggle="table" style="word-break:break-all;">
                                <thead>
                                <tr>
                                    <th data-field="state" data-checkbox="true"></th>
                                    <th data-field="id" data-width="50" data-align="center">ID</th>
                                    <th data-field="title" data-width="300" data-align="left"
                                        data-formatter="titleFormatter">标题
                                    </th>
                                    <th data-field="amount" data-align="center" data-width="50">访问量</th>
                                    <th data-field="state_name" data-align="center" data-width="50"
                                        data-formatter="stateFormatter">状态
                                    </th>
                                    <th data-field="begin_date" data-width="120" data-align="center">问卷开始时间</th>
                                    <th data-field="end_date" data-width="120" data-align="center">问卷结束时间</th>
                                    <th data-field="action" data-align="center" data-width="200"
                                        data-formatter="actionFormatter" data-events="actionEvents">管理操作
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
        $('#contents_query').click(function () {
            $('#table').bootstrapTable('selectPage', 1);
        });

        $('#table').bootstrapTable({
            method: 'get',
            url: '/admin/surveys/table',
            pagination: true,
            pageNumber: 1,
            pageSize: 20,
            pageList: [10, 25, 50, 100],
            sidePagination: 'server',
            clickToSelect: true,
            striped: true,
            queryParams: function (params) {
                var object = $('#forms input').serializeObject();
                object['state'] = $('#state').val();
                object['_token'] = '{{ csrf_token() }}';
                object['offset'] = params.offset;
                object['limit'] = params.limit;
                return object;
            },
        });
        // $('#modal').modal('hide');
        $('#modal').on('hide.bs.modal', function (event) {

        });

        function thumbFormatter(value, row, index) {
            var thumb_html = '<img src="' + row.image_url + '" width="120">';
            return [
                thumb_html
            ].join('');
        }

        function stateFormatter(value, row, index) {
            var style = 'label-primary';
            switch (row.state_name) {
                case '正常':
                    style = 'label-success';
                    break;
                case '已删除':
                    style = 'label-danger';
                    break;
            }
            return [
                '<span class="label ' + style + '">' + row.state_name + '</span>',
            ].join('');
        }

        function titleFormatter(value, row, index) {
            if (row.is_top == 1) {
                return [
                    '<span class="label label-primary">推荐</span><span> </span><a href="/admin/surveys/' + row.id + '" target="_blank">' + row.title + '</a>',
                ]
            }
            else {
                return [
                    '<a href="/admin/surveys/' + row.id + '" target="_blank">' + row.title + '</a>',
                ]
            }
        }

        function actionFormatter(value, row, index) {

            var disabled_del = '';
            if (row.state_name == '已删除') {
                disabled_del = 'disabled="disabled"';
            }

            html =
                '<button class="btn btn-primary btn-xs edit" data-toggle="tooltip" data-placement="top" title="编辑"><i class="fa fa-edit"></i></button>' +
                '<span> </span>';
            if (row.is_top == 0) {
                html += '<button class="btn btn-primary btn-xs top" data-toggle="tooltip" data-placement="top" title="推荐"><i class="fa fa-arrow-up"></i></button>';
            } else {
                html += '<button class="btn btn-primary btn-xs top" data-toggle="tooltip" data-placement="top" title="取消推荐"><i class="fa fa-arrow-down"></i></button>';
            }
            html +=
                '<span> </span>' +
                '<button class="btn btn-info btn-xs count" data-toggle="modal" data-target="#modal_count" title="统计"><i class="fa fa-envelope"></i></button>';
            html +=
                '<span> </span>' +
                '<button class="btn btn-danger btn-xs remove" data-toggle="modal" data-target="#modal"  title="删除"><i class="fa fa-trash"></i></button>';
            return html;
        }

        window.actionEvents = {
            'click .edit': function (e, value, row, index) {
                window.location.href = '/admin/surveys/' + row.id + '/edit';
            },

            'click .count': function (e, value, row, index) {
                $('#modal_title').text('问卷统计');
                $('#window_msg').hide();
                $('.common').prop('id', 'modal_count');

                var url = '/admin/survey/items/' + row.id;
                $.ajax({
                    url: url,
                    type: "get",
                    data: {'_token': '{{ csrf_token() }}'},
                    dataType: 'html',
                    success: function (html) {
                        $('#contents').html(html);
                    }
                });
            },
            'click .top': function (e, value, row, index) {
                $.ajax({
                    url: '/admin/surveys/top',
                    type: 'POST',
                    data: {'_token': '{{ csrf_token() }}', 'id': row.id},
                    success: function (data) {
//                        window.location.reload();
                        window.location.href = '/admin/surveys';
                    }
                })
            },

            'click .remove': function (e, value, row, index) {
                remove_open = true;
                $('#msg').html('您确认删除该条信息吗？');
                $('#modal_remove').show();
                $('#modal_remove').data('id', row.id);
            },
        }

        $("#modal_remove").click(function () {
            var row_id = $(this).data('id');
            if (typeof(row_id) == "undefined") {
                return false;
            }
            var ids = [row_id];
            $.ajax({
                url: '/admin/surveys/state',
                type: 'POST',
                data: {'_token': '{{ csrf_token() }}', 'ids': ids, 'state': '{{ \App\Models\Survey::STATE_DELETED }}'},
                success: function (data) {
                    window.location.href = '/admin/surveys';
                }
            });

        });
    </script>

@endsection