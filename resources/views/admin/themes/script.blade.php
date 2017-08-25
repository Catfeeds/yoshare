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
        console.log(e);
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
                    if (typeof(data.id) != 'undefined') {
                        $('#btn_edit_theme').show();
                    }
                    else {
                        $('#btn_edit_theme').hide();
                    }
                    if (typeof(data.nodes) == 'undefined') {
                        $('#btn_create_file').hide();
                        $('#btn_remove_file').show();

                        filePath = data.path;
                        readFile(data.path);
                        editor.setReadOnly(false);
                    }
                    else {
                        $('#btn_create_file').show();
                        $('#btn_remove_file').hide();
                    }
                }
            });

            $('#tree').treeview('selectNode', [0, {silent: false}]);
        }
    });

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

    function createFile(path) {
        if (path.length == 0) {
            toastrs('info', '<b>请选择文件</b>')
            return;
        }
        $.ajax({
            type: 'post',
            async: false,
            url: '{{ url('admin/themes/file') }}',
            data: {'_token': '{{ csrf_token() }}', 'path': path},
            success: function (data) {
                window.location.reload();
            },
            error: function () {
                toastrs('error', '<b>创建失败</b>')
            }
        });
    }

    function removeFile(path) {
        if (path.length == 0) {
            toastrs('info', '<b>请选择文件</b>')
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
                toastrs('error', '<b>删除失败</b>')
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
                    toastrs('warning', '<b>读取失败: ' + data.message + '</b>')
                }
            },
            error: function () {
                toastrs('error', '<b>读取失败</b>')
            }
        });
    }

    function writeFile(path, data) {
        if (path.length == 0) {
            toastrs('info', '<b>请选择文件</b>')
            return;
        }
        $.ajax({
            type: 'post',
            async: false,
            url: '{{ url('admin/themes/file') }}',
            data: {'_token': '{{ csrf_token() }}', '_method': 'put', 'path': path, 'data': data},
            success: function (data) {
                if (data.code == 200) {
                    toastrs('success', '<b>保存成功</b>')
                }
                else {
                    toastrs('warning', '<b>保存失败: ' + data.message + '</b>')
                }
                editor.focus();
            },
            error: function () {
                toastrs('error', '<b>保存失败</b>')
            }
        });
    }
</script>