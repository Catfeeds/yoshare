@extends('layouts.modal')

@section('handle')
    <div class="row" id="content">
        <div class="no-padding pull-left col-sm-12">
            <div class="box box-info">
                <div class="box-body">
                    <div id="trees"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="modal_tree">确认</button>
    </div>
@endsection

@section('js')
    <script>
        var category_ids = new Array();

        function getTrees(user_id, ids) {
            var url = '/users/tree/' + user_id;
            category_ids = ids;
            $.getJSON(url, function (data) {
                $('#trees').treeview({
                    data: data,
                    selectedIcon: 'glyphicon glyphicon-ok',
                    multiSelect: true,
                    onNodeSelected: function (event, data) {
                        if ($.inArray(data.id, category_ids) == -1) {
                            category_ids.push(data.id);
                            category_ids = $.unique(category_ids);
                        }
                    },
                    onNodeUnselected: function (event, data) {
                        category_ids = $.grep(category_ids, function (val) {
                            return val != data.id;
                        });
                    }
                });
            });
        }

        $("#modal_tree").click(function () {
            var user_id = $(this).data('id');

            $.ajax({
                url: '/users/grant/' + user_id,
                type: 'POST',
                data: {'_token': '{{ csrf_token() }}', 'category_ids': category_ids},
                success: function () {
                    window.location.reload();
                }
            });
        });
    </script>
@endsection