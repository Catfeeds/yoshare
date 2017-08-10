<table id="table" data-toggle="table">
    <thead>
    <tr>
        <th data-field="state" data-checkbox="true"></th>
        @foreach($module->columns as $column)
            @if($column->show)
                <th data-field="{{ $column->type == \App\Models\ModuleField::TYPE_ENTITY ? str_replace('_id', '_name', $column->name) : $column->name }}"
                    data-align="{{ $column->align === 1 ? 'left' : ($column->align === 2 ? 'center' : 'right') }}"
                    data-width="{{ $column->width ? : 60 }}"
                    data-formatter="{{ $column->formatter }}"
                    data-editable="{{ $column->editable }}">{{ $column->label }}</th>
            @endif
        @endforeach
        <th data-field="action" data-align="center" data-width="110" data-formatter="actionFormatter" data-events="actionEvents">操作</th>
    </tr>
    </thead>
</table>
