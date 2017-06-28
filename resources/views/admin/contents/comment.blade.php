<div class="row">
    <div class="col-xs-12">
        <div class="box box-info">
            <div class="box-body">
                <table id="table" data-toggle="table" style="word-break:break-all;">
                    <thead>
                    <tr>
                        <th data-field="id" data-width="50">ID</th>
                        <th data-field="nick_name" data-width="100">会员</th>
                        <th data-field="content" data-width="200" data-formatter="titleFormatter">内容</th>
                        <th data-field="likes" data-width="60">点赞数</th>
                        <th data-field="ip" data-width="90">IP</th>
                        <th data-field="state_name" data-width="60" data-formatter="stateFormatter">状态</th>
                        <th data-field="created_at" data-width="120">发表时间</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $('#table').bootstrapTable({
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
            params.id = '{{ $content_id }}';
            params._token = '{{ csrf_token() }}';
            return params;
        },
    });

    function titleFormatter(value, row, index) {
        return [
            '<p class="content_title"  data-toggle="tooltip" data-placement="top" title="' + row.content + '">' + row.content + '</p>',
        ]
    }

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
</script>