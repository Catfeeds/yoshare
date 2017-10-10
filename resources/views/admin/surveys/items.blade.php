<div class="row">
    <div class="box box-info">
        <div class="box-body">

            <table id="table"
                   data-toggle="table"
                   data-url="/admin/surveys/items/table/{{$survey_id}}"
                   data-pagination="true"
                   data-toolbar="#toolbar">
                <thead>
                <tr>
                    <th data-field="id" data-align="center" data-width="45">ID</th>
                    <th data-field="subject">问卷题目</th>
                    <th data-field="title">问卷选项</th>
                    <th data-field="count" data-align="center" data-width="90" data-editable="true">参与数</th>
                    <th data-field="percent" data-align="center" data-width="120">百分比</th>
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
                row._token = '{{ csrf_token() }}';
                $.ajax({
                    type: "put",
                    url: "/admin/survey/items/" + row.id,
                    data: row,
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