<table id="table" data-toggle="table">
    <thead>
    <tr>
        <th data-field="state" data-checkbox="true"></th>
        @foreach($module->columns as $column)
            @if($column->show)
                <th data-field="{{ isset($column->name) ? $column->name : $field->name }}"
                    data-align="{{ $column->align ? : 'left' }}"
                    data-width="{{ $column->width ? : 60 }}"
                    data-formatter="{{ $column->formatter }}"
                    data-editable="{{ $column->editable }}">{{ $column->title }}</th>
            @endif
        @endforeach
        <th data-field="action" data-align="center" data-width="110" data-formatter="actionFormatter" data-events="actionEvents">操作</th>
    </tr>
    </thead>
</table>
