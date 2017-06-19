<div class="modal fade" id="modal_copy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
                <h4 class="modal-title">选择栏目</h4>
            </div>
            <div class="modal-body" id="msg">
                <div class="row" id="content">
                    <div class="no-padding pull-left col-sm-4">
                        <div class="box box-info">
                            <div class="box-body">
                                <div id="trees"></div>
                            </div>
                        </div>
                    </div>
                    <div class="no-padding pull-right col-sm-7" style="margin-right: 15px">
                        <table class="table table-hover box box-info" id="add_copy_cata">
                            <thead>
                            <tr>
                                <th>已选栏目</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="modal_copys">确认</button>
            </div>
        </div>
    </div>
</div>
<script>
    var push_open = false;
    var category_ids = [];
    var url = '/contents/categories';
    function getCategory() {
        $.getJSON(url, function (data) {
            $('#trees').treeview({
                data: data,
                onNodeSelected: function (event, data) {
                    if (push_open == true || $.inArray(data.id, category_ids) > -1) {
                        return false;
                    }
                    category_ids.push(data.id);
                    category_ids = $.unique(category_ids);
                    var html = '<tr id="category' + data.id + '">' +
                        '<td>' + data.text + '<span class="close">' +
                        '<span class="glyphicon glyphicon-remove pull-right" style="font-size: 14px;" onclick="removeCategory(' + data.id + ')"></span>' +
                        '</span></td></tr>';
                    $('#add_copy_cata').append(html);
                },
            });
        });
    }

    $("#modal_copys").delegate(this, "click", function () {
        if (category_ids.length == 0) {
            return false;
        }

        var rows = $('#table').bootstrapTable('getSelections');
        var ids = [];
        $(rows).each(function (k, obj) {
            ids[k] = obj.id;
        });

        $.ajax({
            url: '/contents/copy',
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}', 'category_ids': category_ids, 'ids': ids},
            success: function () {
                window.location.href = '/contents?category_id=' + category_id;
            }
        });
    });

    /* 复制 */
    function modalCopy() {
        var rows = $('#table').bootstrapTable('getSelections');
        if (rows.length > 0) {
//            $('#copy').data('target', '#modalCopy');
            $('#copy').attr('data-target', '#modal_copy');
            getCategory();
        } else {
            $('#copy').removeAttr('data-target');
        }
    }

    function removeCategory(category_id) {
        var category_id_str = "#category" + category_id;
        category_ids = $.grep(category_ids, function (val) {
            return val != category_id;
        });
        $(category_id_str).remove();
    }
</script>