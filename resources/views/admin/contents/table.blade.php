<table id="table" data-toggle="table">
    <thead>
    <tr data-formatter="submitFormatter">
        <th data-field="state" data-checkbox="true"></th>
        @foreach($model->grid->fields as $field)
            @if($field->grid->show)
                <th data-field="{{ isset($field->grid->name) ? $field->grid->name : $field->name }}"
                    data-align="{{ $field->grid->align ? : 'left' }}"
                    data-width="{{ $field->grid->width ? : 60 }}"
                    data-formatter="{{ $field->grid->formatter }}"
                    data-editable="{{ $field->grid->editable }}">{{ $field->grid->title }}</th>
            @endif
        @endforeach
        <th data-field="action" data-align="center" data-width="110" data-formatter="actionFormatter" data-events="actionEvents">操作</th>
    </tr>
    </thead>
</table>
