<div class="cb-toolbar">操作:</div>
<div class="btn-group margin-bottom">
    <input type="hidden" name="state" id="state" value=""/>
    <button class="btn btn-primary btn-xs margin-r-5" id="create" onclick="create()">新增</button>
</div>
<div class="btn-group margin-bottom pull-right">
    <button type="button" class="btn btn-info btn-xs margin-r-5 filter" id="" value="">全部</button>
    <button type="button" class="btn btn-success btn-xs margin-r-5 filter"
            value="{{ \App\Models\Member::STATE_ENABLED }}">已启用
    </button>
    <button type="button" class="btn btn-danger btn-xs margin-r-5 filter"
            value="{{ \App\Models\Member::STATE_DISABLED }}">已禁用
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
            {!! Form::label('nick_name', '昵称:', ['class' => 'control-label cb-toolbar']) !!}
            <div class="col-sm-9" style="padding: 0;">
                {!! Form::text('nick_name', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-4" style="padding: 0">
            {!! Form::label('mobile', '手机号:', ['class' => 'control-label cb-toolbar']) !!}
            <div class="col-sm-7" style="padding: 0;">
                {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
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
            <button type="button" class="btn btn-info btn-sm" id="articles_query">查 询</button>
        </div>
    </div>
</div>

<script>
    /* 新增 */
    function create() {
        window.location.href = '/admin/members/create'
    }

    /* 筛选 */
    $('.filter').click(function () {
        var object = $('#forms input').serializeObject();
        object['state'] = $(this).val();
        object['_token'] = '{{ csrf_token() }}';
        $('#state').val(object['state']);
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