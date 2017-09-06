<div class="row">
    <div class="col-xs-12">
        <div class="box box-info">

            <div class="box-body">
                <table id="comment_table" data-toggle="table" style="word-break:break-all;">
                    <thead>
                    <tr>
                        <th data-field="id" data-width="60">ID</th>
                        <th data-field="nick_name" data-width="100">会员</th>
                        <th data-field="content" data-width="380" data-formatter="titleFormatter">评论</th>
                        <th data-field="ip" data-width="125">IP</th>
                        <th data-field="state_name" data-width="60" data-formatter="stateFormatter">状态</th>
                        <th data-field="created_at" data-width="130">发表时间</th>
                        <th data-field="action" data-width="100" data-align="center" data-formatter="actionFormatter"
                            data-events="actionEvents"> 操作
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $('#comment_table').bootstrapTable({
        method: 'get',
        url: '/admin/comments/table',
        pagination: true,
        pageNumber: 1,
        pageSize: 15,
        pageList: [10, 25, 50, 100],
        sidePagination: 'server',
        clickToSelect: true,
        striped: true,
        queryParams: function (params) {
            params.id = '{{ $id }}';
            params._token = '{{ csrf_token() }}';
            return params;
        },
    });

    function stateFormatter(value, row, index) {
        var style = 'label-primary';
        switch (row.state_name) {
            case '未审核':
                style = 'label-primary';
                break;
            case '已审核':
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

    function actionFormatter(value, row, index) {
        var disabled_del = '';
        switch (row.state_name) {
            case '已删除':
                disabled_del = 'disabled="disabled"';
                break;
        }
        return [
            '<a class="remove" href="javascript:void(0)"><button class="btn btn-danger btn-xs" ' + disabled_del + ' >删除</button></a>'
        ].join('');
    }

    window.actionEvents = {
        'click .remove': function (e, value, row, index) {
            var ids = [row.id];
            $.ajax({
                url: '/admin/comments/state/' + '{{ \App\Models\Comment::STATE_DELETED }}',
                type: 'POST',
                data: {'_token': '{{ csrf_token() }}', 'ids': ids},
                success: function () {
                    $('#comment_table').bootstrapTable('selectPage', 1);
                    $('#comment_table').bootstrapTable('refresh');

                    toastrs('success', '删除成功！');
                }
            });
        },
        'click .comment': function (e, value, row, index) {
            $('#replies_title').text('管理员回复列表');

            var url = '/admin/comments/' + row.id;
            $.ajax({
                url: url,
                type: "get",
                data: {'_token': '{{ csrf_token() }}'},
                dataType: 'html',
                success: function (html) {
                    $('#reply_contents').html(html);
                }
            });
        }
    };

    function titleFormatter(value, row, index) {
        return [
            '<p class="content_title" data-toggle="tooltip" data-placement="top" title="' + row.content + '">' + row.content + '</p>',
        ]
    }

    function toastrs(state, message) {
        toastr.options = {
            'closeButton': true,
            'positionClass': 'toast-top-center',
        };
        toastr[state](message);
    }
</script>