@extends('layouts.master')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                投票管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">投票管理</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="box box-info">
                    <div class="box-body">
                        @include('layouts.flash')
                        @include('layouts.confirm', ['message' => '您确认删除该条信息吗？'])
                        @include('votes.toolbar')
                        @include('layouts.modal', ['id' => 'modal_count'])
                        <table id="table" data-toggle="table">
                            <thead>
                            <tr class="parent">
                                <th data-field="state" data-checkbox="true"></th>
                                <th data-field="id" data-align="center" data-width="30">ID</th>
                                <th data-field="title" data-formatter="titleFormatter">标题</th>
                                <th data-field="amount" data-align="center" data-width="30">参与人数</th>
                                <th data-field="begin_date" data-align="center" data-width="120">投票开始日期</th>
                                <th data-field="end_date" data-align="center" data-width="120">投票截止日期</th>
                                <th data-field="state_name" data-align="center" data-width="60"
                                    data-formatter="stateFormatter">状态
                                </th>
                                <th data-field="created_at" data-align="center" data-width="120">发表时间</th>
                                <th data-field="action" data-align="center" data-width="150"
                                    data-formatter="actionFormatter" data-events="actionEvents">管理操作
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


@section('js')
    <script>

        $('#contents_query').click(function () {
            $('#table').bootstrapTable('selectPage', 1);
        });

        $('#table').bootstrapTable({
            method: 'get',
            url: '/votes/table',
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


        function actionFormatter(value, row, index) {
            var disabled_del = '';
            if (row.state_name == '已删除') {
                disabled_del = 'disabled="disabled"';
            }

            html =
                '<button class="btn btn-primary btn-xs edit" data-toggle="tooltip" data-placement="top" title="编辑"><i class="fa fa-edit"></i></button>' +
                '<span> </span>';
            if (row.is_top == '0' || row.is_top == null) {
                html += '<button class="btn btn-primary btn-xs top" data-toggle="tooltip" data-placement="top" title="推荐"><i class="fa fa-arrow-up"></i></button>';
            } else {
                html += '<button class="btn btn-primary btn-xs top" data-toggle="tooltip" data-placement="top" title="取消推荐"><i class="fa fa-arrow-down"></i></button>';
            }
            html +=
                '<span> </span>' +
                '<button class="btn btn-info btn-xs count" data-toggle="modal" data-target="#modal_count" title="统计"><i class="fa fa-envelope"></i></button>' +
                '<span> </span>' +
                '<button class="btn btn-danger btn-xs remove" data-toggle="modal" data-target="#modal"  title="删除"><i class="fa fa-trash"></i></button>';

            return html;

        }

        function titleFormatter(value, row, index) {
            if (row.is_top == '1') {
                return [
                    '<span class="label label-success">推荐</span><span> </span><a href="/votes/' + row.id + '" target="_blank">' + row.title + '</a>',
                ]
            }
            else {
                return [
                    '<a href="/votes/' + row.id + '" target="_blank">' + row.title + '</a>',
                ]
            }
        }

        $("#modal_remove").click(function () {
            var row_id = $(this).data('id');
            if (typeof(row_id) == "undefined") {
                return false;
            }
            var token = document.getElementsByTagName('meta')['csrf-token'].getAttribute('content');
            $.ajax({
                type: 'DELETE',
                data: {'_token': token},
                url: '/votes/' + row_id,
                success: function (data) {
                    window.location.href = '/votes';
                }
            });
        });

        window.actionEvents = {
            'click .edit': function (e, value, row, index) {
                window.location.href = '/votes/' + row.id + '/edit';
            },

            'click .count': function (e, value, row, index) {
                $('#modal_title').text('投票统计');
                $('#window_msg').hide();
                $('.common').prop('id', 'modal_count');

                var url = '/voteitems/' + row.id;
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

            'click .remove': function (e, value, row, index) {
                remove_open = true;
                $('#msg').html('您确认删除该条信息吗？');
                $('#modal_remove').show();
                $('#modal_remove').data('id', row.id);
            },

            'click .top': function (e, value, row, index) {
                $.ajax({
                    url: '/votes/top/' + row.id,
                    type: 'POST',
                    data: {'_token': '{{ csrf_token() }}'},
                    success: function (data) {
                        window.location.reload();
                    }
                })
            }
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

    </script>
@endsection