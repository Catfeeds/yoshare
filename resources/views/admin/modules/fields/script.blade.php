
<script>
    function booleanFormatter(value, row, index) {
        if (value == 1) {
            return '<i class="fa fa-check"></i>';
        } else {
            return '';
        }
    }

    function actionFormatter(value, row, index) {
        return '<button class="btn btn-primary btn-xs edit" data-toggle="tooltip" data-placement="top" title="编辑"><i class="fa fa-edit"></i></button><span> </span>' +
            '<button class="btn btn-danger btn-xs remove" data-toggle="modal" data-placement="top" title="删除" data-target="#modal"><i class="fa fa-trash"></i></button><span> </span>';
    }

    window.actionEvents = {
        'click .edit': function (e, value, row, index) {
            $('#form').attr('action', '/admin/modules/fields/' + row.id);
            $('#method').val('PUT');
            $('#name').val(row.name);
            $('#title').val(row.title);
            $('#type').val(row.type);
            $('#default').val(row.default);
            $('#required').bootstrapSwitch('state', row.required)
            $('#system').bootstrapSwitch('state', row.system)
            $('#modal_form').modal('show');
        },
        'click .remove': function (e, value, row, index) {
            $('#btn_confirm').data('id', row.id);
        },
    };

    $('#btn_create').click(function () {
        $('#form').attr('action', '/admin/modules/fields');
        $('#method').val('POST');
        $('#name').val('');
        $('#title').val('');
        $('#type').val(1);
        $('#default').val('');
        $('#required').bootstrapSwitch('state', false);
        $('#system').bootstrapSwitch('state', false);
    });

    $("#btn_confirm").click(function () {
        var row_id = $(this).data('id');
        $.ajax({
            url: '/admin/modules/fields/' + row_id,
            method: 'post',
            data: {'_token': '{{ csrf_token() }}', '_method': 'delete'},
            success: function (data) {
                window.location.reload();
            }
        });
    });

</script>