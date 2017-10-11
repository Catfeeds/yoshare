<script>
    var filePath = '';

    var editor = ace.edit('editor');
    editor.session.setMode('ace/mode/php');
    editor.setTheme('ace/theme/github');
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true,
        enableEmmet: true
    });
    editor.session.on('change', function (e) {
    });
    editor.$blockScrolling = Infinity;
    editor.commands.addCommand({
        name: 'save',
        bindKey: {win: "Ctrl-S", "mac": "Cmd-S"},
        exec: function (editor) {
            writeFile(filePath, editor.session.getValue());
        }
    });
    editor.setReadOnly(true);

    $.ajax({
        type: 'get',
        async: false,
        url: '/admin/themes/tree',
        success: function (data) {
            $('#tree').treeview({
                expandIcon: 'fa fa-folder-o',
                collapseIcon: 'fa fa-folder-open-o',
                showTags: true,
                data: data,
                onNodeSelected: function (event, data) {
                    $('#btn_edit_theme').hide();
                    $('#btn_create_file').hide();
                    $('#btn_remove_file').hide();
                    $('#link_preview').text('');

                    if (typeof(data.id) != 'undefined') {
                        if (data.type == 'module') {
                            //显示编辑主题按钮
                            $('#btn_edit_theme').show();
                        }
                    }
                    else {
                    }

                    if (typeof(data.nodes) == 'undefined') {
                        //末端节点
                        $('#btn_remove_file').show();

                        //读取文件内容
                        filePath = data.path;
                        readFile(data.path);
                        editor.setReadOnly(false);


                        //刷新模块下文件的编辑界面
                        if (typeof(data.module_id) != 'undefined' && data.module_id > 0) {
                            refresh(data.module_id, data.text);
                        }

                        //显示首页地址
                        if (typeof(data.tags) != 'undefined' && data.tags[0] == '首页'){
                            $('#link_preview').text('/index.html');
                            $('#link_preview').attr('href', $('#link_preview').text());
                        }
                    }
                    else {
                        $('#btn_create_file').show();
                    }
                }
            });

            $('#tree').treeview('selectNode', [0, {silent: false}]);
        }
    });

    function createFile(name) {
        var nodes = $('#tree').treeview('getSelected');
        var path = nodes[0].path + '/';
        var extension = nodes[0].extension;
        if (path.length == 0) {
            toast('info', '<b>请选择文件夹</b>')
            return;
        }
        $.ajax({
            type: 'post',
            url: '{{ url('admin/themes/file') }}',
            data: {'_token': '{{ csrf_token() }}', 'path': path, 'name' : name, 'extension':extension},
            success: function (data) {
                window.location.reload();
            },
            error: function () {
                toast('error', '<b>创建失败</b>')
            }
        });
    }

    $('#btn_create_file').click(function () {
        var nodes = $('#tree').treeview('getSelected');
        if (nodes.length > 0) {
            $('#form_file input[name="path"]').val(nodes[0].path + '/');
            $('#form_file #path').text(nodes[0].path + '/');
            $('#form_file input[name="extension"]').val(nodes[0].extension);
            $('#form_file #extension').text(nodes[0].extension);
        }
    });

    $('#btn_remove_file').click(function () {
        var nodes = $('#tree').treeview('getSelected');
        if (nodes.length > 0) {
            removeFile(nodes[0].path);
        }
    });

    $('#btn_create_theme').click(function () {
        $('#form_theme').attr('action', '/admin/themes');
        $('#method').val('POST');
        $('#name').val('');
        $('#title').val('');
    });

    $('#btn_edit_theme').click(function () {
        var nodes = $('#tree').treeview('getSelected');
        if (nodes.length > 0) {
            $('#form_theme').attr('action', '/admin/themes/' + nodes[0].id);
            $('#method').val('PUT');
            $('#name').val(nodes[0].text);
            $('#title').val(nodes[0].tags[1]);
            $('#modal_theme').modal('show');
        }
    });

    function removeFile(path) {
        if (path.length == 0) {
            toast('info', '<b>请选择文件</b>')
            return;
        }
        $.ajax({
            type: 'post',
            async: false,
            url: '{{ url('admin/themes/file') }}',
            data: {'_token': '{{ csrf_token() }}', '_method': 'delete', 'path': path},
            success: function () {
                window.location.reload();
            },
            error: function () {
                toast('error', '<b>删除失败</b>')
            }
        });
    }

    function readFile(path) {
        $.ajax({
            type: 'get',
            async: false,
            url: '/admin/themes/file?path=' + path,
            success: function (data) {
                if (data.code == 200) {
                    editor.setValue(data.data, -1);
                    editor.focus();
                }
                else {
                    toast('warning', '<b>读取失败: ' + data.message + '</b>')
                }
            },
            error: function () {
                toast('error', '<b>读取失败</b>')
            }
        });
    }

    function writeFile(path, data) {
        if (path.length == 0) {
            toast('info', '<b>请选择文件</b>');
            return;
        }
        $.ajax({
            type: 'post',
            async: false,
            url: '{{ url('admin/themes/file') }}',
            data: {'_token': '{{ csrf_token() }}', '_method': 'put', 'path': path, 'data': data},
            success: function (data) {
                if (data.code == 200) {
                    toast('success', '<b>保存成功</b>')
                }
                else {
                    toast('warning', '<b>保存失败: ' + data.message + '</b>')
                }
                editor.focus();
            },
            error: function () {
                toast('error', '<b>保存失败</b>')
            }
        });
    }

    function refresh(module_id, page) {
        $.ajax({
            type: 'get',
            url: '{{ url('admin/themes/modules') }}' + '/' + module_id,
            success: function (res) {
                if (res.code == 200) {

                    //清空
                    $('#list_var li').remove();

                    //站点
                    $('#list_var').append('<li><a href="javascript:void(0)" class="code" data-code="$site">站点</a></li>');
                    $('#list_var').append('<li><a href="javascript:void(0)" class="code" data-code="$site->title">站点->标题</a></li>');
                    $('#list_var').append('<li><a href="#" class="code" data-code="$site->company">站点->单位</a></li>');

                    if (page == 'index.blade.php') {
                        //列表页
                        $('#link_preview').text(res.data.table_name+'/index.html');
                        $('#link_preview').attr('href', $('#link_preview').text());

                        $('#list_var').append('<li class="divider"></li>');
                        $('#list_var').append('<li><a href="javascript:void(0)" class="code" data-code="$module">模块</a></li>');
                        $('#list_var').append('<li><a href="javascript:void(0)" class="code" data-code="$module->title">模块->标题</a></li>');
                        $('#list_var').append('<li class="divider"></li>');
                        $('#list_var').append('<li><a href="javascript:void(0)" class="code" data-code="$' + res.data.name.toLowerCase() + 's">' + res.data.title + '(集合)' + '</a></li>');
                    } else if (page == 'category.blade.php') {
                        //栏目页
                        $('#link_preview').text(res.data.table_name+'/category-1.html');
                        $('#link_preview').attr('href', $('#link_preview').text());

                        $('#list_var').append('<li class="divider"></li>');
                        $('#list_var').append('<li><a href="javascript:void(0)" class="code" data-code="$category">栏目</a></li>');
                        $('#list_var').append('<li><a href="javascript:void(0)" class="code" data-code="$category->name">栏目->名称</a></li>');
                        $('#list_var').append('<li class="divider"></li>');
                        $('#list_var').append('<li><a href="javascript:void(0)" class="code" data-code="$' + res.data.name.toLowerCase() + 's">' + res.data.title + '(集合)' + '</a></li>');
                    } else if (page == 'detail.blade.php') {
                        //详情页
                        $('#link_preview').text(res.data.table_name+'/detail-1.html');
                        $('#link_preview').attr('href', $('#link_preview').text());

                        $('#list_var').append('<li class="divider"></li>');
                        for (var i = 0; i < res.data.fields.length; i++) {
                            $('#list_var').append('<li><a href="javascript:void(0)" class="code" data-code="' + res.data.name.toLowerCase() + '->' + res.data.fields[i].name + '">' + res.data.title + '->' + res.data.fields[i].title + '</a></li>');
                        }
                    }

                    //添加事件
                    $('.code').click(function () {
                        editor.insert($(this).data('code'));
                    });
                }
                else {
                    toast('warning', '<b>获取模块信息失败: ' + res.message + '</b>')
                }
            },
            error: function () {
                toast('error', '<b>获取模块信息失败</b>')
            }
        });
    }

    $('.code').click(function () {
        editor.insert($(this).data('code'));
    });
</script>