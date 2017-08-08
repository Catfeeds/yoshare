<script>
    function stateFormatter(value, row, index) {
        var style = 'label-primary';
        switch (row.state_name) {
            case '已启用':
                style = 'label-success';
                break;
            case '已禁用':
                style = 'label-danger';
                break;
        }
        return [
            '<span class="label ' + style + '">' + row.state_name + '</span>',
        ].join('');
    }

    function actionFormatter(value, row, index) {
        return '<button class="btn btn-primary btn-xs edit" data-toggle="tooltip" data-placement="top" title="编辑"><i class="fa fa-edit"></i></button><span> </span>' +
            '<button class="btn btn-primary btn-xs field" data-toggle="tooltip" data-placement="top" title="字段"><i class="fa fa-list"></i></button><span> </span>';
    }

    window.actionEvents = {
        'click .edit': function (e, value, row, index) {
            $('#form').attr('action', '/admin/modules/' + row.id);
            $('#method').val('PUT');
            $('#name').val(row.name);
            $('#title').val(row.title);
            $('#model_class').val(row.model_class);
            $('#controller_class').val(row.controller_class);
            $('#view_path').val(row.view_path);
            $('#modal_form').modal('show');
        },
        'click .field': function (e, value, row, index) {
            window.location.href = '/admin/modules/fields/' + row.id;
        },
    };

    $('#btn_create').click(function () {
        $('#form').attr('action', '/admin/modules');
        $('#method').val('POST');
        $('#name').val('');
        $('#title').val('');
        $('#model_class').val('');
        $('#controller_class').val('');
        $('#view_path').val('');
    });

</script>