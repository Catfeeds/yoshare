@extends('admin.layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                系统管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">系统设置</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-body">
                            <table id="table"
                                   data-toggle="table"
                                   data-url="options/table"
                                   data-pagination="true"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-show-toggle="true"
                                   data-show-columns="true"
                                   data-toolbar="#toolbar">
                                <thead>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        function sendAjax(params) {
            $.ajax({
                url: '/admin/options/' + params.pk + '/save',
                data: params,
                success: function () {
                    $('#table').bootstrapTable('refresh');
                },
                error: function () {
                    alert('Error');
                },
            });
        }

        $('#table').bootstrapTable({
            onLoadSuccess: function (options) {
                $(options.data).each(function (k, option) {
                    switch (option.type) {
                        case 1:
                            $('.boolean').editable({
                                source: [
                                    {value: 0, text: '否'},
                                    {value: 1, text: '是'}
                                ],
                                url: function (params) {
                                    sendAjax(params)
                                },
                            });
                            break;
                        case 2:
                            $('.text').editable({
                                type: 'text',
                                url: function (params) {
                                    sendAjax(params)
                                },
                            });
                            break;
                        case 3:
                            $('.textarea').editable({
                                showbuttons: 'bottom',
                                url: function (params) {
                                    sendAjax(params)
                                },
                            });
                            break;
                        case 4:
                            $('.date').editable({
                                format: 'yyyy-mm-dd',
                                url: function (params) {
                                    sendAjax(params)
                                },
                            });
                            break;
                        case 5:
                            $('.datetime').editable({
                                placement: 'top',
                                combodate: {
                                    firstItem: 'name'
                                },
                                url: function (params) {
                                    sendAjax(params)
                                },
                            });
                            break;
                        case 6:
                            $('.single').editable({
                                source: [
                                    {value: 1, text: '男'},
                                    {value: 2, text: '女'}
                                ],
                                url: function (params) {
                                    sendAjax(params)
                                },
                            });
                            break;
                        case 7:
                            $('.select').editable({
                                inputclass: 'input-large',
                                select2: {
                                    tags: ['英语', '汉语', '法语', '新西兰语'],
                                    width: '200px',
                                    tokenSeparators: [",", " "]
                                },
                                url: function (params) {
                                    sendAjax(params)
                                },
                            });
                            break;
                        default:
                            alert('Error');
                    }
                });
            },
            columns: [
                {
                    field: 'name',
                    title: '名称'
                }, {
                    field: 'value',
                    title: '值',
                    formatter: function (value, row, index) {
                        switch (row.type) {
                            case 1:
                                return '<a href="javascript:void(0);" class="boolean" data-type="select" data-field="boolean" data-name="' + row.name + '" data-pk="' + row.id + '" data-value="' + row.value + '" data-title="布尔型"></a>';
                                break;
                            case 2:
                                return '<a href="javascript:void(0);" class="text" data-type="text" data-field="text" data-name="' + row.name + '" data-pk="' + row.id + '" data-title="文本">' + row.value + '</a>'
                                break;
                            case 3:
                                return '<a href="javascript:void(0);" class="textarea" data-type="textarea" data-field="textarea" data-name="' + row.name + '" data-pk="' + row.id + '" data-placeholder="" data-title="多行文本">' + row.value + '</a>'
                                break;
                            case 4:
                                return '<a href="javascript:void(0);" class="date" data-type="combodate" data-field="date" data-name="' + row.name + '" data-pk="' + row.id + '" data-value="' + row.value + '" data-format="YYYY-MM-DD" data-viewformat="YYYY-MM-DD" data-template="YYYY-MM-DD" data-title="日期"></a>';
                                break;
                            case 5:
                                return '<a href="javascript:void(0);" class="datetime" data-type="combodate" data-field="datetime" data-name="' + row.name + '" data-pk="' + row.id + '" data-value="' + row.value + '" data-format="YYYY-MM-DD HH:mm" data-viewformat="YYYY-MM-DD HH:mm" data-template="YYYY-MM-DD HH:mm" data-title="日期时间"></a>'
                                break;
                            case 6:
                                return '<a href="javascript:void(0);" class="single" data-type="select" data-field="single" data-name="' + row.name + '" data-pk="' + row.id + '" data-value="' + row.value + '" data-title="单选"></a>';
                                break;
                            case 7:
                                return '<a href="javascript:void(0);" class="select" data-type="select2" data-field="select" data-name="' + row.name + '" data-pk="' + row.id + '" data-title="多选">' + row.value + '</a>'
                                break;
                            default:
                                alert('Error');
                        }
                    }
                }, {
                    field: "type",
                    title: "类型",
                    editable: {
                        type: 'select',
                        source: [
                            {value: 1, text: '布尔'},
                            {value: 2, text: "文本"},
                            {value: 3, text: "多行文本"},
                            {value: 4, text: "日期"},
                            {value: 5, text: "日期时间"},
                            {value: 6, text: "单选"},
                            {value: 7, text: "多选"},
                        ]
                    }
                }, {
                    field: "option",
                    title: "选项",
                }, {
                    field: 'site_name',
                    title: '站点'
                }
            ],
            onEditableSave: function (field, row, old, $el) {
                row._token = '{{ csrf_token() }}';
                $.ajax({
                    url: '/admin/options/' + row.id + '/save',
                    data: row,
                    success: function () {
                        $('#table').bootstrapTable('refresh');
                    },
                    error: function (data) {
                        alert('Error');
                    },
                });
            }
        });
    </script>
@endsection