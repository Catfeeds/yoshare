<div class="cb-toolbar">操作:</div>
<div class="btn-group margin-bottom pull-right">
    <button type="button" class="btn btn-default btn-xs margin-r-5" id="query" data-toggle="modal" data-target="#modal_query">查询</button>
</div>

<script>
    /* 筛选 */
    $('.filter').click(function () {
        $('#state').val($(this).val());
        $('#table').bootstrapTable('selectPage', 1);
    });
</script>