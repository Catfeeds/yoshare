@extends('admin.layouts.master')

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
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-body">
                            @include('admin.layouts.flash')
                            @include('admin.layouts.confirm', ['message' => '您确认删除该条信息吗？'])
                            @include('admin.votes.toolbar')
                            @include('admin.layouts.modal', ['id' => 'modal_count'])
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
                                    <th data-field="published_at" data-align="center" data-width="120">发布时间</th>
                                    <th data-field="action" data-align="center" data-width="150"
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
@endsection


@section('js')
    <script>
        /* 启动排序 */
        $('#btn_sort').click(function () {
            if ($('#btn_sort').hasClass('active')) {
                $('#btn_sort').removeClass('active');
                $('#btn_sort').text('排序');
                $('#table tbody').sortable('disable');
                $('#table tbody').enableSelection();
                toast('info', '<b>已禁用排序功能</b>')
            }
            else {
                $('#btn_sort').addClass('active');
                $('#btn_sort').text('排序(已启用)');
                $('#table tbody').sortable('enable');
                $('#table tbody').disableSelection();
                toast('info', '<b>已启用排序功能</b>')
            }
        });

        /* 表格 */
        var place_index;
        var select_index;
        var original_y;
        var move_down = 1;
        $('#table').bootstrapTable({
            method: 'get',
            url: '/admin/votes/table',
            pagination: true,
            pageNumber: 1,
            pageSize: 20,
            pageList: [10, 25, 50, 100],
            sidePagination: 'server',
            clickToSelect: true,
            striped: true,
            onLoadSuccess: function (data) {
                $('#modal_query').modal('hide');
                $('#table tbody').sortable({
                    cursor: 'move',
                    axis: 'y',
                    revert: true,
                    start: function (e, ui) {
                        select_index = ui.item.attr('data-index');
                        original_y = e.pageY;
                    },
                    sort: function (e, ui) {
                        if (e.pageY > original_y) {
                            place_index = $(this).find('tr').filter('.ui-sortable-placeholder').prev('tr').attr('data-index');
                            move_down = 1;
                        }
                        else {
                            place_index = $(this).find('tr').filter('.ui-sortable-placeholder').next('tr').attr('data-index');
                            move_down = 0;
                        }
                    },
                    update: function (e, ui) {
                        var select_id = data.rows[select_index].id;
                        var place_id = data.rows[place_index].id;

                        if (select_id == place_id) {
                            return;
                        }

                        $.ajax({
                            url: '/admin/votes/sort',
                            type: 'get',
                            async: true,
                            data: {select_id: select_id, place_id: place_id, move_down: move_down},
                            success: function (data) {
                                if (data.status_code != 200) {
                                    $('#table tbody').sortable('cancel');
                                    $('#table').bootstrapTable('refresh');
                                }
                            },
                        });
                    }
                });
                $('#table tbody').sortable('disable');
            },
            queryParams: function (params) {
                params.state = $('#state').val();
                params._token = '{{ csrf_token() }}';
                return params;
            },
        });

        function titleFormatter(value, row, index) {
            return [
                row.title,
            ]
        }

        function actionFormatter(value, row, index) {
            var disabled_del = '';
            if (row.state_name == '已删除') {
                disabled_del = 'disabled="disabled"';
            }
            return [
                '<a class="edit" href="javascript:void(0)"><button class="btn btn-primary btn-xs">编辑</button></a>',
                '<span> </span>',
                '<a class="count" href="javascript:void(0)"><button class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal_count">统计</button></a>',
                '<span> </span>',
                '<a class="remove" href="javascript:void(0)"><button class="btn btn-danger btn-xs" ' + disabled_del + ' data-toggle="modal" data-target="#modal">删除</button></a>',
            ].join('');
        }

        $("#modal_remove").click(function () {
            var row_id = $(this).data('id');
            if (typeof(row_id) == "undefined") {
                return false;
            }
            var ids = [row_id];
            $.ajax({
                url: '/admin/votes/state',
                type: 'POST',
                data: {'_token': '{{ csrf_token() }}', 'ids': ids, 'state': '{{ \App\Models\Comment::STATE_DELETED }}'},
                success: function (data) {
                    $('#modal').modal('hide');
                    $('#table').bootstrapTable('refresh');
                }
            });
        });

        window.actionEvents = {
            'click .edit': function (e, value, row, index) {
                window.location.href = '/admin/votes/' + row.id + '/edit';
            },

            'click .count': function (e, value, row, index) {
                $('#modal_title').text('投票统计');
                $('#window_msg').hide();
                $('.common').prop('id', 'modal_count');

                var url = '/admin/votes/items/' + row.id;
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
        }

        function stateFormatter(value, row, index) {
            var style = 'label-primary';
            switch (row.state_name) {
                case '未发布':
                    style = 'label-primary';
                    break;
                case '已发布':
                    style = 'label-success';
                    break;
                case '已撤回':
                    style = 'label-warning';
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