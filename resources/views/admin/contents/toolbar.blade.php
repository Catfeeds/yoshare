<div class="cb-toolbar">操作:</div>
<div class="btn-group margin-bottom">
    <input type="hidden" name="state" id="state" value=""/>
    <button class="btn btn-primary btn-xs margin-r-5" id="btn_create" onclick="create()">新增</button>
    <button class="btn btn-success btn-xs margin-r-5 state" value="{{ \App\Models\Content::STATE_PUBLISHED }}">发布</button>
    <button class="btn btn-warning btn-xs margin-r-5 state" value="{{ \App\Models\Content::STATE_CANCELED }}">撤回</button>
    <button class="btn btn-info btn-xs margin-r-5" data-toggle="modal" onclick="copy()">推荐</button>
    <button class="btn btn-danger btn-xs margin-r-5" id="btn_delete" value="{{ \App\Models\Content::STATE_DELETED }}" onclick="remove()" data-toggle="modal" data-target="#modal">删除</button>
    <button class="btn btn-default btn-xs margin-r-5" id="btn_sort">排序</button>
</div>
<div class="btn-group margin-bottom pull-right">
    <button type="button" class="btn btn-info btn-xs margin-r-5 filter" data-active="btn-info" value="">全部</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5 filter" data-active="btn-primary" value="{{ \App\Models\Content::STATE_NORMAL }}">未发布</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5 filter" data-active="btn-success" value="{{ \App\Models\Content::STATE_PUBLISHED }}">已发布</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5 filter" data-active="btn-warning" value="{{ \App\Models\Content::STATE_CANCELED }}">已撤回</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5 filter" data-active="btn-danger" value="{{ \App\Models\Content::STATE_DELETED }}">已删除</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5" data-toggle="modal" data-target="#modal_query"><span class="fa fa-search"></span></button>
</div>

<script>
    function create() {
        window.location.href = '/admin/contents/create/' + category_id;
    }

    var remove_open = false;
    $("#modal_remove").click(function () {
        if (remove_open == true) {
            return false;
        }

        var state = $('#delete').val();
        var rows = $('#table').bootstrapTable('getSelections');

        var ids = [];
        for (var i = 0; i < rows.length; i++) {
            ids[ids.length] = rows[i].id;
        }

        $.ajax({
            url: '/admin/contents/state/' + state,
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}', 'ids': ids},
            success: function () {
                $('#modal').modal('hide');
                $('#table').bootstrapTable('refresh');
            }
        });
    });

    function remove() {
        remove_open = false;
        var rows = $('#table').bootstrapTable('getSelections');
        if (rows.length > 0) {
            $('#msg').html('您确认删除这<strong><span class="text-danger">' + rows.length + '</span></strong>条信息吗？');
            $('#modal_remove').show();
        } else {
            $('#msg').html('请选择要删除的数据！');
            $('#modal_remove').hide();
        }
    }

    /* 操作 */
    $('.state').click(function () {
        var state = $(this).val();
        var rows = $('#table').bootstrapTable('getSelections');

        var ids = [];
        for (var i = 0; i < rows.length; i++) {
            ids[ids.length] = rows[i].id;
        }

        if (ids.length > 0) {
            $.ajax({
                url: '/admin/contents/state/' + state,
                type: 'POST',
                data: {'_token': '{{ csrf_token() }}', 'ids': ids},
                success: function () {
                    $('#table').bootstrapTable('refresh');
                }
            });
        }
    });

    /* 筛选 */
    $('.filter').click(function () {
        var value = $(this).val();
        $('#state').val(value);
        $('#table').bootstrapTable('selectPage', 1);

        //改变按钮样式
        $('.filter').removeClass('btn-primary btn-info btn-success btn-danger btn-warning');
        $('.filter').addClass('btn-default');
        $(this).removeClass('btn-default');
        $(this).addClass($(this).data('active'));
    });

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