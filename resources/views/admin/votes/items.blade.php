<div class="row">
    <div class="box box-info">
        <div class="box-body">

            <table id="table"
                   data-toggle="table"
                   data-url="/admin/votes/items/table/{{$vote_id}}"
                   data-show-export="true"
                   data-pagination="true"
                   data-toolbar="#toolbar">
                <thead>
                <tr>
                    <th data-field="id" data-align="center" data-width="45">ID</th>
                    <th data-field="title">标题</th>
                    <th data-field="amount" data-align="center" data-width="90">投票数</th>
                    {{--<th data-field="percent" data-align="center" data-width="120">百分比</th>--}}
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    (function () {
        $('#table').bootstrapTable({
            onEditableSave: function (field, row, old, $el) {
                $.ajax({
                    type: "put",
                    url: "/admin/votes/items/" + row.id,
                    data: {'_token': '{{ csrf_token() }}', 'initial_amount': row.initial_amount},
                    success: function (data, status) {
                        $('#table').bootstrapTable('refresh');
                    },
                    error: function (data) {
                        alert('Error');
                    },
                });
            }
        });
    })();
</script>