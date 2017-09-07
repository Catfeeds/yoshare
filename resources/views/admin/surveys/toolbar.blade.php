<div class="cb-toolbar">操作:</div>
<div class="btn-group margin-bottom">
    <input type="hidden" name="state" id="state" value=""/>
    <button class="btn btn-success btn-xs margin-r-5" id="create" onclick="create()">新增</button>
    <button class="btn btn-danger btn-xs margin-r-5" id="delete"
            value="{{ \App\Models\Survey::STATE_DELETED }}" onclick="modalRemove()" data-toggle="modal"
            data-target="#modal">删除
    </button>
</div>
<div class="btn-group margin-bottom pull-right">
    <button type="button" class="btn btn-info btn-xs margin-r-5 filter" id="" value="">全部</button>
    <button type="button" class="btn btn-primary btn-xs margin-r-5 filter"
            value="{{ \App\Models\Survey::STATE_NORMAL }}">正常
    </button>
    <button type="button" class="btn btn-danger btn-xs margin-r-5 filter"
            value="{{ \App\Models\Survey::STATE_DELETED }}">已删除
    </button>
    <button type="button" class="btn btn-default btn-xs margin-r-5" id="query" onclick="openOrClose('forms')">查询
    </button>
</div>


<div class="form-horizontal form-group-sm" id="forms" style="display: none;">
    <div class="btn-group margin-bottom col-md-6" style="padding: 0;">
        <div class="col-md-5" style="padding: 0">
            {!! Form::label('title', '标题:', ['class' => 'control-label cb-toolbar']) !!}
            <div class="col-sm-9" style="padding: 0;">
                {!! Form::text('title', null, ['class' => 'form-control']) !!}
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
    /* 新增 */
    function create() {
        window.location.href = '/admin/surveys/create'
    }

    var remove_open = false;
    $("#modal_remove").delegate(this, "click", function () {
        if (remove_open == true) {
            return false;
        }

        var state = $('#delete').val();
        var rows = $('#table').bootstrapTable('getSelections');

        var ids = new Array();
        for (var i = 0; i < rows.length; i++) {
            ids[ids.length] = rows[i].id;
        }

        $.ajax({
            url: '/admin/surveys/state/' + state,
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}', 'ids': ids},
            success: function () {
                window.location.reload();
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

        var ids = new Array();
        for (var i = 0; i < rows.length; i++) {
            ids[ids.length] = rows[i].id;
        }
        if (ids.length > 0) {
            $.ajax({
                url: '/surveys/state/' + state,
                type: 'POST',
                data: {'_token': '{{ csrf_token() }}', 'ids': ids},
                success: function () {
                    window.location.reload();
                }
            });
        }
    });

    /* 筛选 */
    $('.filter').click(function () {
        $('#state').val($(this).val());
        $('#table').bootstrapTable('selectPage', 1);
        $('#table').bootstrapTable('refresh', {
            query: {
                state: $(this).val(),
                _token: '{{ csrf_token() }}'
            }
        });
    });

    /* 筛选推荐 */
    $('.recommend').click(function () {
        var object = $('#forms input').serializeObject();
        object['recommend'] = $('.recommend').val();
        object['_token'] = '{{ csrf_token() }}';
        $('#table').bootstrapTable('selectPage', 1);
        $('#table').bootstrapTable('refresh', {query: object});
    });

    function openOrClose(id_name_str) {
        var id_name = '#' + id_name_str;
        if ($(id_name).css('display') == 'block') {
            $(id_name).slideUp(350);
        } else {
            $(id_name).slideDown(350);
        }
    }

    $('.date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        locale: "zh-CN",
        toolbarPlacement: 'bottom',
        showClear: true,
    });
</script>