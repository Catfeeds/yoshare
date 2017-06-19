<table id="table" data-toggle="table">
    <thead>
    <tr data-formatter="submitFormatter">
        <th data-field="state" data-checkbox="true"></th>
        @foreach($fields as $field)
            @if($field->table->show)
                <th data-field="{{ $field->name }}" data-align="{{ $field->table->align ? : 'left' }}" data-width="{{ $field->table->width ? : 60 }}" data-formatter="{{ $field->table->formatter }}" data-editable="{{ $field->table->editable }}">{{ $field->alias }}</th>
            @endif
        @endforeach
        <th data-field="action" data-align="center" data-width="110" data-formatter="actionFormatter" data-events="actionEvents">操作</th>
        {{--<th data-field="id" data-align="center" data-width="30">ID</th>--}}
        {{--<th data-field="title" class="title" data-width="280" data-formatter="titleFormatter">--}}
            {{--标题--}}
        {{--</th>--}}
        {{--<th data-field="tags" data-align="center" data-width="60" data-formatter="tagsFormatter">--}}
            {{--标签--}}
        {{--</th>--}}
        {{--<th data-field="keywords" data-align="center" data-width="60" data-formatter="keywordsFormatter">--}}
            {{--关键字--}}
        {{--</th>--}}
        {{--<th data-field="clicks" data-align="center" data-width="45" data-editable="true">--}}
            {{--点击量--}}
        {{--</th>--}}
        {{--<th data-field="views" data-align="center" data-width="45" data-editable="true">--}}
            {{--初始量--}}
        {{--</th>--}}
        {{--<th data-field="user_name" data-align="center" data-width="45">操作员</th>--}}
        {{--<th data-field="state_name" data-align="center" data-width="45" data-formatter="stateFormatter">--}}
            {{--状态--}}
        {{--</th>--}}
        {{--<th data-field="published_at" data-align="center" data-width="110">发布时间</th>--}}
    </tr>
    </thead>
</table>
<script>
    var place_index;
    var select_index;
    var original_y;
    var move_down = 1;
    var category_id = 0;

    $('#table').bootstrapTable({
        method: 'get',
        url: '/contents/table',
        pagination: true,
        pageNumber: {{ isset($page) ? $page : 1 }},
        pageSize: 100,
        pageList: [20, 50, 100, 200],
        sidePagination: 'server',
        clickToSelect: true,
        striped: true,

        onLoadSuccess: function (data) {
            $('#modal_query').modal('hide');
            $('#table tbody').sortable({
                cursor: 'move',
                axis: 'y',
                revert: true,
                start: function (e, ui) {
                    select_index = ui.item.attr('data-index');
                    original_y = e.pageY;
                },
                sort: function (e, ui) {
                    if (e.pageY > original_y) {
                        place_index = $(this).find('tr').filter('.ui-sortable-placeholder').prev('tr').attr('data-index');
                        move_down = 1;
                    }
                    else {
                        place_index = $(this).find('tr').filter('.ui-sortable-placeholder').next('tr').attr('data-index');
                        move_down = 0;
                    }
                },
                update: function (e, ui) {
                    var select_id = data.rows[select_index].id;
                    var place_id = data.rows[place_index].id;

                    if (select_id == place_id) {
                        return;
                    }

                    $.ajax({
                        url: '/contents/sort',
                        type: 'get',
                        async: true,
                        data: {select_id: select_id, place_id: place_id, move_down: move_down},
                        success: function (data) {
                            if (data.status_code != 200) {
                                $('#table tbody').sortable('cancel');
                                $('#table').bootstrapTable('refresh');
                            }
                        },
                    });
                }
            });
            $('#table tbody').sortable('disable');
        },
        onEditableSave: function (field, row, old, $el) {
            $.ajax({
                url: "/contents/" + row.id + '/save',
                data: {'_token': '{{ csrf_token() }}', 'clicks': row.clicks, 'views': row.views},
                success: function (data, status) {
                },
                error: function (data) {
                    alert('Error');
                },
            });
        },
        queryParams: function (params) {
            if (category_id == 0) {
                return false;
            }
            var object = $('#forms input').serializeObject();
            object['state'] = $('#state').val();
            object['offset'] = params.offset;
            object['limit'] = params.limit;
            object['category_id'] = category_id;
            return object;
        },
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
        var tags_str = row.tags;
        var html = '';

        if (row.is_top == '1') {
            html += '<span class="label label-success">{{ \App\Models\Content::TAG_TOP }}</span><span> </span>';
        }

        return [
            '<a href="/contents/' + row.id + '" target="_blank">' + html + row.title + '</a>',
        ]
    }

    function tagsFormatter(value, row, index) {
        var tags = row.tags;

        var arr = tags.split(' ');

        if(arr.length > 0){
            var s = arr[0];
            if (s.length > 4) {
                s = s.substring(0, 4)
            }
            return '<p data-toggle="tooltip" data-placement="top" class="text-red" title="' + tags + '">' + s + '</p>';
        }
    }

    function keywordsFormatter(value, row, index) {
        var keywords = row.keywords;

        var arr = keywords.split(' ');

        if(arr.length > 0){
            var s = arr[0];
            if (s.length > 4) {
                s = s.substring(0, 4)
            }
            return '<p data-toggle="tooltip" data-placement="top" title="' + keywords + '">' + s + '</p>';
        }
    }

    function actionFormatter(value, row, index) {
        var html = '<button class="btn btn-primary btn-xs edit" data-toggle="tooltip" data-placement="top" title="编辑"><i class="fa fa-edit"></i></button><span> </span>' +
            '<button class="btn btn-info btn-xs push" data-toggle="modal" data-target="#modal_push"><i class="fa fa-envelope" data-toggle="tooltip" data-placement="top" title="推送"></i></button><span> </span>' +
            '<button class="btn btn-info btn-xs comment" data-toggle="modal" data-target="#modal_comment"><i class="fa fa-comment" data-toggle="tooltip" data-placement="top" title="查看评论"></i></button><span> </span>';

        if (row.is_top) {
            html += '<a class="top" href="javascript:void(0)"><button class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="取消{{ \App\Models\Content::TAG_TOP }}">置顶</button></a>';
        } else {
            html += '<a class="top" href="javascript:void(0)"><button class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="{{ \App\Models\Content::TAG_TOP }}">置顶</button></a>';
        }

        return html;
    }

    window.actionEvents = {
        'click .edit': function (e, value, row, index) {
            var page = $('#table').bootstrapTable('getOptions').pageNumber;
            window.location.href = '/contents/' + row.id + '/edit?page=' + page;
        },

        'click .push': function (e, value, row, index) {
            $('#content_id').val(row.id);
        },

        'click .top': function (e, value, row, index) {
            $.ajax({
                url: '/contents/top/' + row.id,
                type: 'POST',
                data: {'_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $('#table').bootstrapTable('refresh');
                }
            })
        },

        'click .tag': function (e, value, row, index) {
            $.ajax({
                url: '/contents/tag/' + row.id,
                type: 'POST',
                data: {'_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $('#table').bootstrapTable('refresh');
                }
            })
        },

        'click .comment': function (e, value, row, index) {
            $('#modal_title').text('查看评论');

            var url = '/contents/comments/' + row.id;
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
</script>