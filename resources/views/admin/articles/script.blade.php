<script>
    var category_id = 0;

    $.ajax({
        type: 'get',
        async: false,
        url: '/admin/articles/categories',
        success: function (data) {
            $('#tree').treeview({
                data: data,
                searchResultColor: 'white',
                levels: 4,
                onNodeSelected: function (event, data) {
                    category_id = data.id;
                    $('#category_id').val(data.id);
                    $('#table').bootstrapTable('refresh');
                }
            });

            if (getNodeIndex(parseInt(getQueryString('category_id')), data) >= 0) {
                $('#tree').treeview('selectNode', [nodeIndex, {silent: false}]);
            }
            else{
                $('#tree').treeview('selectNode', [0, {silent: false}]);
            }
        }
    });

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

    function titleFormatter(value, row, index) {
        return [
            '<a href="/admin/articles/' + row.id + '" target="_blank">' + row.title + '</a>',
        ]
    }

    function actionFormatter(value, row, index) {
        return '<button class="btn btn-primary btn-xs edit" data-toggle="tooltip" data-placement="top" title="编辑"><i class="fa fa-edit"></i></button><span> </span>';
    }

    function updateRow(field, row, old, $el) {
        $.ajax({
            url: '/admin/articles/' + row.id + '/save',
            data: {'_token': '{{ csrf_token() }}', 'clicks': row.clicks},
            success: function (data, status) {
            },
            error: function (data) {
                alert('Error');
            },
        });
    }

    window.actionEvents = {
        'click .edit': function (e, value, row, index) {
            window.location.href = '{{ $base_url }}/' + row.id + '/edit';
        },

        'click .comment': function (e, value, row, index) {
            $('#modal_title').text('查看评论');
            $('#window_msg').hide();

            var url = '/admin/articles/comments/' + row.id;
            $.ajax({
                url: url,
                type: "get",
                data: {'_token': '{{ csrf_token() }}'},
                dataType: 'html',
                success: function (html) {
                    $('#contents').html(html);
                }
            });
        }
    };

    /* 启动排序 */
    $('#btn_sort').click(function () {
        if ($('#btn_sort').hasClass('active')) {
            $('#btn_sort').removeClass('active');
            $('#btn_sort').text('排序');
            $('#table tbody').sortable('disable');
            $('#table tbody').enableSelection();
            toastrs('info', '<b>已禁用排序功能</b>')
        }
        else {
            $('#btn_sort').addClass('active');
            $('#btn_sort').text('排序(已启用)');
            $('#table tbody').sortable('enable');
            $('#table tbody').disableSelection();
            toastrs('info', '<b>已启用排序功能</b>')
        }
    });
</script>