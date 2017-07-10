<div class="cb-toolbar">操作:</div>
<div class="btn-group margin-bottom">
    <input type="hidden" name="state" id="state" value=""/>
    <button class="btn btn-primary btn-xs margin-r-5" id="create" onclick="location='/admin/members/create';">新增</button>
</div>
<div class="btn-group margin-bottom pull-right">
    <button type="button" class="btn btn-info btn-xs margin-r-5 filter" id="" value="">全部</button>
    <button type="button" class="btn btn-success btn-xs margin-r-5 filter"
            value="{{ \App\Models\Member::STATE_ENABLED }}">已启用
    </button>
    <button type="button" class="btn btn-danger btn-xs margin-r-5 filter"
            value="{{ \App\Models\Member::STATE_DISABLED }}">已禁用
    </button>
    <button type="button" class="btn btn-default btn-xs margin-r-5" id="query" data-toggle="modal" data-target="#modal_query">查询</button>
</div>

<script>
    /* 筛选 */
    $('.filter').click(function () {
        $('#state').val($(this).val());
        $('#table').bootstrapTable('selectPage', 1);
    });
</script>