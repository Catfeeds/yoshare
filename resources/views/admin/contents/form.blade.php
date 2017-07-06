<ul id="tabs" class="nav nav-tabs">
    @foreach($model->tabs as $tab)
        <li class="{{ $loop->first ? 'active' : '' }}">
            <a href="#{{ 'tab_' . $tab->name }}" data-toggle="tab">{{ $tab->title }}</a>
        </li>
    @endforeach
</ul>
<div class="tab-content">
    @foreach($model->tabs as $tab)
        <div id="{{ 'tab_' . $tab->name }}" class="tab-pane fade in {{ $loop->first ? 'active' : '' }} padding-t-15">
            <?php $position = 0; ?>
            @foreach($tab->fields as $key => $field)
                @if ($field->editor->show)
                    @if ($position == 0)
                        <div class="form-group">
                            @endif
                            @if($field->editor->type == 'html')
                                <div class="col-sm-{{ $field->editor->columns }}">
                                    {!! Form::text($field->name, null, ['class' => 'form-control', 'id' => $field->name]) !!}
                                </div>
                                <script>
                                    CKEDITOR.replace('{{ $field->name }}', {
                                        height: {{ $field->editor->rows * 20 }},
                                        filebrowserUploadUrl: '{{ url('files/upload') }}?_token={{csrf_token()}}'
                                    });
                                </script>
                            @elseif($field->editor->type == 'datetime')
                                {!! Form::label($field->name, $field->title . ':', ['class' => 'control-label col-sm-1']) !!}
                                <div class="col-sm-{{ $field->editor->columns }}">
                                    <div class='input-group date'>
                                        {!! Form::text($field->name, null, ['class' => 'form-control']) !!}
                                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                                    </div>
                                </div>
                            @elseif($field->editor->type == 'select')
                                {!! Form::label($field->name, $field->title . ':', ['class' => 'control-label col-sm-1']) !!}
                                <div class="col-sm-{{ $field->editor->columns }}">
                                    {!! Form::select($field->name, $field->editor->options, null, ['class' => 'form-control']) !!}
                                </div>
                            @elseif($field->editor->type == 'textarea')
                                {!! Form::label($field->name, $field->title . ':', ['class' => 'control-label col-sm-1']) !!}
                                <div class="col-sm-{{ $field->editor->columns }}">
                                    {!! Form::textarea('summary', null, ['class' => 'form-control', 'rows' => $field->editor->rows]) !!}
                                </div>
                            @elseif($field->editor->type == 'images')
                                <div class="col-sm-{{ $field->editor->columns }}">
                                    {!! Form::hidden($field->name, null, ['class' => 'form-control', 'id' => $field->name]) !!}
                                </div>
                            @elseif($field->editor->type == 'videos')
                                <div class="col-sm-{{ $field->editor->columns }}">
                                    {!! Form::hidden($field->name, null, ['class' => 'form-control', 'id' => $field->name]) !!}
                                </div>
                            @else
                                {!! Form::label($field->name, $field->title . ':', ['class' => 'control-label col-sm-1']) !!}
                                <div class="col-sm-{{ $field->editor->columns }}">
                                    {!! Form::text($field->name, null, ['class' => 'form-control']) !!}
                                </div>
                            @endif
                            <?php
                            $position += $field->editor->columns + 1;
                            if ($loop->last || $position + $tab->fields[$key + 1]->editor->columns + 1 > 12) {
                                $position = 0;
                            }
                            ?>
                            @if($position == 0 || $position == 12)
                        </div>
                    @endif
                    @if($field->editor->type == 'image')
                        <div class="form-group">
                            <label for="{{ $field->name . '_file' }}" class="control-label col-sm-1">上传图片:</label>
                            <div class="col-sm-11">
                                <input id="{{ $field->name . '_file' }}" name="{{ $field->name . '_file' }}" type="file" class="file" data-upload-url="/admin/files/upload?type=image">
                            </div>
                        </div>
                        <script>
                            var {{ $field->name }}_preview = $('#{{ $field->name }}').val();
                            if ({{ $field->name }}_preview.length > 0) {
                                {{ $field->name }}_preview = ['<img height="240" src="' + {{ $field->name }}_preview + '" class="kv-preview-data file-preview-image">'];
                            }
                            $('#{{ $field->name . '_file' }}').fileinput({
                                language: 'zh',
                                uploadExtraData: {_token: '{{ csrf_token() }}'},
                                allowedFileExtensions: ['jpg', 'gif', 'png'],
                                maxFileSize: 10240,
                                maxFileCount: 1,
                                resizeImage: true,
                                maxImageWidth: 640,
                                maxImageHeight: 960,
                                resizePreference: 'width',
                                initialPreview: {{ $field->name }}_preview,
                                initialPreviewConfig: [{key: 1}],
                                deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
                                previewFileType: 'image',
                                overwriteInitial: true,
                                browseClass: 'btn btn-success',
                                browseIcon: '<i class=\"glyphicon glyphicon-picture\"></i>',
                                removeClass: "btn btn-danger",
                                removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
                                uploadClass: "btn btn-info",
                                uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>'
                            }).on('fileuploaded', function (event, data) {
                                $('#{{ $field->name }}').val(data.response.data);
                            }).on('filedeleted', function (event, key) {
                                $('#{{ $field->name }}').val('');
                            });
                        </script>
                    @elseif($field->editor->type == 'images')
                        <div class="form-group">
                            <label for="image_file" class="control-label col-sm-1">上传图集:</label>
                            <div class=" col-sm-11">
                                <input id="{{ $field->name . '_file' }}" name="{{ $field->name . '_file' }}[]" type="file" class="file file-loading" data-upload-url="/admin/files/upload?type=image" multiple>
                            </div>
                        </div>
                        <script>
                            var {{ $field->name }}_preview = [];
                            var {{ $field->name }}_config = [];
                            @if(isset($content))
                                @foreach($content->images() as $image)
                                    {{ $field->name }}_preview.push('<img height="240" src="{{ $image->url }}" class="kv-preview-data file-preview-image">');
                                    {{ $field->name }}_config.push({key: '{{ $image->id }}', url: '{{ $image->url }}'});
                                @endforeach
                            @endif
                            $('#{{ $field->name . '_file' }}').fileinput({
                                language: 'zh',
                                uploadExtraData: {_token: '{{ csrf_token() }}'},
                                allowedFileExtensions: ['jpg', 'gif', 'png'],
                                maxFileSize: 10240,
                                resizeImage: true,
                                maxImageWidth: 960,
                                maxImageHeight: 640,
                                initialPreview: {{ $field->name }}_preview,
                                initialPreviewConfig: {{ $field->name }}_config,
                                previewFileType: 'image',
                                overwriteInitial: false,
                                deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
                                browseClass: 'btn btn-success',
                                browseIcon: '<i class=\"glyphicon glyphicon-picture\"></i>',
                                removeClass: "btn btn-danger",
                                removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
                                uploadClass: "btn btn-info",
                                uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>'
                            });

                            $(document).ready(function () {
                                $('#submit').click(function () {
                                    var configs = $('#{{ $field->name . '_file' }}').fileinput('getPreview').config;
                                    var urls = '';
                                    for (var i = 0; i < configs.length; i++) {
                                        if (i > 0) {
                                            urls += ',';
                                        }
                                        urls += configs[i].url;
                                    }
                                    $('#{{ $field->name }}').val(urls);
                                });
                            });
                        </script>
                    @elseif($field->editor->type == 'video')
                        <div class="form-group">
                            <label for="{{ $field->name . '_file' }}" class="control-label col-sm-1">上传视频:</label>
                            <div class="col-sm-11">
                                <input id="{{ $field->name . '_file' }}" name="{{ $field->name . '_file' }}" type="file" class="file" data-upload-url="/admin/files/upload?type=video">
                            </div>
                        </div>
                        <script>
                            var {{ $field->name }}_preview = $('#{{ $field->name }}').val();
                            if ({{ $field->name }}_preview.length > 0) {
                                {{ $field->name }}_preview = ['<video height="300" controls="controls" src="' + {{ $field->name }}_preview + '"></video>'];
                            }
                            $('#{{ $field->name . '_file' }}').fileinput({
                                language: 'zh',
                                uploadExtraData: {_token: '{{ csrf_token() }}'},
                                allowedFileExtensions: ['mp4', 'mpg', 'mpeg', 'avi', 'wav', 'mp3'],
                                maxFileSize: 1048576,
                                initialPreview: {{ $field->name }}_preview,
                                initialPreviewConfig: [{key: 1}],
                                previewFileType: 'video',
                                deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
                                browseClass: 'btn btn-success',
                                browseIcon: '<i class=\"glyphicon glyphicon-hd-video\"></i>',
                                removeClass: "btn btn-danger",
                                removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
                                uploadClass: "btn btn-info",
                                uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>'
                            }).on('fileuploaded', function (event, data) {
                                $('#video_url').val(data.response.data);
                            }).on('filedeleted', function (event, key) {
                                $('#video_url').val('');
                            });
                        </script>
                    @elseif($field->editor->type == 'videos')
                        <div class="form-group">
                            <label for="image_file" class="control-label col-sm-1">上传视频集:</label>
                            <div class=" col-sm-11">
                                <input id="{{ $field->name . '_file' }}" name="{{ $field->name . '_file' }}[]" type="file" class="file file-loading" data-upload-url="/admin/files/upload?type=video" multiple>
                            </div>
                        </div>
                        <script>
                            var {{ $field->name }}_preview = [];
                            var {{ $field->name }}_config = [];
                            @if(isset($content))
                                @foreach($content->videos() as $video)
                                    {{ $field->name }}_preview.push('<video height="240" controls="controls" src="{{ $video->url }}"></video>');
                                    {{ $field->name }}_config.push({key: '{{ $video->id }}', url: '{{ $video->url }}'});
                                @endforeach
                            @endif
                            $('#{{ $field->name . '_file' }}').fileinput({
                                language: 'zh',
                                uploadExtraData: {_token: '{{ csrf_token() }}'},
                                allowedFileExtensions: ['mp4', 'mpg', 'mpeg', 'avi', 'wav', 'mp3'],
                                maxFileSize: 1048576,
                                initialPreview: {{ $field->name }}_preview,
                                initialPreviewConfig: {{ $field->name }}_config,
                                previewFileType: 'video',
                                overwriteInitial: false,
                                deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
                                browseClass: 'btn btn-success',
                                browseIcon: '<i class=\"glyphicon glyphicon-hd-video\"></i>',
                                removeClass: "btn btn-danger",
                                removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
                                uploadClass: "btn btn-info",
                                uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>'
                            });

                            $(document).ready(function () {
                                $('#submit').click(function () {
                                    var configs = $('#{{ $field->name . '_file' }}').fileinput('getPreview').config;
                                    var urls = '';
                                    for (var i = 0; i < configs.length; i++) {
                                        if (i > 0) {
                                            urls += ',';
                                        }
                                        urls += configs[i].url;
                                    }
                                    $('#{{ $field->name }}').val(urls);
                                });
                            });
                        </script>
                    @elseif($field->editor->type == 'audio')
                        <div class="form-group">
                            <label for="{{ $field->name . '_file' }}" class="control-label col-sm-1">上传音频:</label>
                            <div class="col-sm-11">
                                <input id="{{ $field->name . '_file' }}" name="{{ $field->name . '_file' }}" type="file" class="file" data-upload-url="/admin/files/upload?type=audio">
                            </div>
                        </div>
                        <script>
                            var {{ $field->name }}_preview = $('#{{ $field->name }}').val();
                            if ({{ $field->name }}_preview.length > 0) {
                                {{ $field->name }}_preview = ['<audio height="100" controls="controls" src="' + {{ $field->name }}_preview + '"></audio>'];
                            }
                            $('#{{ $field->name . '_file' }}').fileinput({
                                language: 'zh',
                                uploadExtraData: {_token: '{{ csrf_token() }}'},
                                allowedFileExtensions: ['wav', 'mp3'],
                                maxFileSize: 1048576,
                                initialPreview: {{ $field->name }}_preview,
                                previewFileType: 'audio',
                                initialPreviewConfig: [{key: 1}],
                                deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
                                browseClass: 'btn btn-success',
                                browseIcon: '<i class=\"glyphicon glyphicon-music\"></i>',
                                removeClass: "btn btn-danger",
                                removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
                                uploadClass: "btn btn-info",
                                uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>'
                            }).on('fileuploaded', function (event, data) {
                                $('#{{ $field->name }}').val(data.response.data);
                            }).on('filedeleted', function (event, key) {
                                $('#{{ $field->name }}').val('');
                            });
                        </script>
                    @endif
                @endif
            @endforeach
        </div>
    @endforeach
</div>
<div class="box-footer">
    <button type="button" class="btn btn-default" onclick="location.href='/admin/contents?category_id={{$category_id}}}}';"> 取　消</button>
    <button type="submit" class="btn btn-info pull-right" id="submit">保　存</button>
</div>

<script>
    $(document).ready(function () {
        $('.date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: "zh-CN",
            toolbarPlacement: 'bottom',
            showClear: true,
        });

        $('#submit').click(function () {
            var ret = true;
            $('.file').each(function() {
                var files = $(this).fileinput('getFileStack');

                if (files.length > 0) {
                    return ret = toastrs('请先上传文件!');
                }
            });

            return ret;
        });
    });
</script>
