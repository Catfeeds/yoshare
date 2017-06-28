<div class="cb-toolbar">操作:</div>
<div class="btn-group margin-bottom">
    <input type="hidden" name="state" id="state" value=""/>
    <button class="btn btn-primary btn-xs margin-r-5" id="create" onclick="create()">新增</button>
    <button class="btn btn-success btn-xs margin-r-5 action"
            value="{{ \App\Models\Content::STATE_PUBLISHED }}">发布
    </button>
    <button class="btn btn-warning btn-xs margin-r-5 action"
            value="{{ \App\Models\Content::STATE_CANCELED }}">撤回
    </button>
    <button class="btn btn-info btn-xs margin-r-5" id="copy" onclick="modalCopy()" data-toggle="modal"
            data-target="#modalCopy">推荐
    </button>
    <button class="btn btn-danger btn-xs margin-r-5" id="delete"
            value="{{ \App\Models\Content::STATE_DELETED }}" onclick="modalRemove()" data-toggle="modal"
            data-target="#modal">删除
    </button>
    <button class="btn btn-default btn-xs margin-r-5" id="sort" onclick="enableSort()">排序</button>
</div>
<div class="btn-group margin-bottom pull-right">
    <button type="button" class="btn btn-info btn-xs margin-r-5 filter" id="" value="">全部</button>
    <button type="button" class="btn btn-primary btn-xs margin-r-5 filter"
            value="{{ \App\Models\Content::STATE_NORMAL }}">未发布
    </button>
    <button type="button" class="btn btn-success btn-xs margin-r-5 filter"
            value="{{ \App\Models\Content::STATE_PUBLISHED }}">已发布
    </button>
    <button type="button" class="btn btn-warning btn-xs margin-r-5 filter"
            value="{{ \App\Models\Content::STATE_CANCELED }}">已撤回
    </button>
    <button type="button" class="btn btn-danger btn-xs margin-r-5 filter"
            value="{{ \App\Models\Content::STATE_DELETED }}">已删除
    </button>
    <button type="button" class="btn btn-default btn-xs margin-r-5" id="query" onclick="openOrClose('forms')">查询
    </button>
</div>

<div class="form-horizontal form-group-sm" id="forms" style="display: none;">
    <div class="btn-group margin-bottom col-md-6" style="padding: 0;">
        <div class="col-md-3" style="padding: 0">
            {!! Form::label('id', 'ID:', ['class' => 'control-label cb-toolbar']) !!}
            <div class="col-sm-8" style="padding: 0;">
                {!! Form::text('id', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-5" style="padding: 0">
            {!! Form::label('title', '标题:', ['class' => 'control-label cb-toolbar']) !!}
            <div class="col-sm-9" style="padding: 0;">
                {!! Form::text('title', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-4" style="padding: 0">
            {!! Form::label('username', '操作员:', ['class' => 'control-label cb-toolbar']) !!}
            <div class="col-sm-7" style="padding: 0;">
                {!! Form::text('username', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    <div class="btn-group margin-bottom col-md-6 pull-right" style="padding: 0">
        <div class="col-md-11" style="width: 88%;padding: 0;">
            <div class="col-md-6" style="padding: 0">
                {!! Form::label('start_date', '时间:', ['class' => 'control-label cb-toolbar']) !!}
                <div class='input-group col-md-9 date'>
                    {!! Form::text('start_date', null, ['class' => 'form-control date']) !!}
                    <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
                </div>
            </div>
            <div class="col-md-6" style="padding: 0">
                {!! Form::label('start_date', '至', ['class' => 'control-label cb-toolbar']) !!}
                <div class='input-group col-md-9 date'>
                    {!! Form::text('end_date', null, ['class' => 'form-control date']) !!}
                    <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
             </span>
                </div>
            </div>
        </div>
        <div class="col-md-1 text-center" style="width: 12%;padding: 0">
            <button type="button" class="btn btn-info btn-sm" id="contents_query">查 询</button>
        </div>
    </div>
</div>

<script>
    function create() {
        window.location.href = '/admin/contents/create/' + category_id;
    }

    var remove_open = false;
    $("#modal_remove").delegate(this, "click", function () {
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

    function modalRemove() {
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
    $('.action').click(function () {
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
        var object = $('#forms input').serializeObject();
        object['state'] = $(this).val();
        object['_token'] = '{{ csrf_token() }}';
        $('#state').val(object['state']);
        $('#table').bootstrapTable('selectPage', 1);
        $('#table').bootstrapTable('refresh', {query: object});
    });

    function enableSort() {
        if ($('#sort').hasClass('active')) {
            $('#sort').removeClass('active');
            $('#sort').text('排序');
            $('#table tbody').sortable('disable');
            $('#table tbody').enableSelection();
            toastrs('<b>已禁用排序功能</b>')
        }
        else {
            $('#sort').addClass('active');
            $('#sort').text('排序(已启用)');
            $('#table tbody').sortable('enable');
            $('#table tbody').disableSelection();
            toastrs('<b>已启用排序功能</b>')
        }
    }

    function openOrClose(id_name_str) {
        var id_name = '#' + id_name_str;
        if ($(id_name).css('display') == 'block') {
            $(id_name).slideUp(350);
        } else {
            $(id_name).slideDown(350);
        }
    }

    function toastrs(message) {
        toastr.options = {
            'closeButton': true,
            'positionClass': 'toast-bottom-right',
        };
        toastr['info'](message);
    }

    $('.date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        locale: "zh-CN",
        toolbarPlacement: 'bottom',
        showClear: true,
    });
</script>