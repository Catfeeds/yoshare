<ul id="tabs" class="nav nav-tabs">
    <li class="active">
        <a href="#tabHome" data-toggle="tab">基本信息</a>
    </li>
    <li>
        <a href="#tabContent" data-toggle="tab">投票选项</a>
    </li>
</ul>
@include('admin.layouts.modal', ['id' => 'img_preview'])
<div id="tabContents" class="tab-content">
    <div id="tabHome" class="tab-pane fade in active padding-t-15">
        <div class="form-group">
            <label class="col-sm-1 control-label">标题</label>
            <div class="col-sm-11">
                {!! Form::text('title', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('most', '多选数量:', ['class' => 'control-label pull-left col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('most', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('link', '外链:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-1">
                {!! Form::select('link_type', \App\Models\Vote::getLinkTypes(), null, ['class' => 'form-control','onchange'=>'return showLink(this.value,true)']) !!}
            </div>
            <div class="col-sm-4" id="link"></div>
        </div>

        <div class="form-group">
            {!! Form::label('begin_date', '开始日期:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-5">
                <div class='input-group date' id='begin_date'>
                    {!! Form::text('begin_date', null, ['class' => 'form-control']) !!}
                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                </div>
            </div>
            {!! Form::label('end_date', '截止日期:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-5">
                <div class='input-group date' id='end_date'>
                    {!! Form::text('end_date', null, ['class' => 'form-control']) !!}
                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('image_url', '图片地址:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-11">
                {!! Form::text('image_url', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="image_file" class="control-label col-sm-1">上传图片:</label>
            <div class=" col-sm-11">
                <input id="image_file" name="image_file" type="file" class="file" data-preview-file-type="text"
                       data-upload-url="/admin/files/upload?width=640">
            </div>
        </div>

        <div class="form-group">
            <label for="image_file" class="control-label col-sm-1">正文:</label>
            <div class="col-sm-11">
                {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    <div id="tabContent" class="tab-pane fade padding-t-15">
        @if(isset($vote))
            <div class="form-group">
                <label class="col-sm-1 control-label">投票类型</label>
                <div class="col-sm-9 control-label">
                    <div class="pull-left">
                        <input type="radio" name="multiple" class="radios"
                               @if($vote->multiple==\App\Models\Vote::MULTIPLE_FALSE) value="{{$vote->multiple}}"
                               checked
                               @else value="{{\App\Models\Vote::MULTIPLE_FALSE}}" @endif> 单选&nbsp;&nbsp;
                        <input type="radio" name="multiple" class="radios"
                               @if($vote->multiple==\App\Models\Vote::MULTIPLE_TRUE) value="{{$vote->multiple}}" checked
                               @else value="{{\App\Models\Vote::MULTIPLE_TRUE}}" @endif> 多选
                    </div>
                </div>
                <div class="col-sm-2">
                    <button type='button' class="btn btn-success btn-flat pull-right" onclick="appendFile()">投票选项 ＋
                    </button>
                </div>
            </div>
        @else
            <div class="form-group">
                <label class="col-sm-1 control-label">投票类型</label>
                <div class="col-sm-9 control-label">
                    <div class="pull-left">
                        <input type="radio" name="multiple" class="radios" value="{{\App\Models\Vote::MULTIPLE_FALSE}}"
                               checked> 单选&nbsp;&nbsp;
                        <input type="radio" name="multiple" class="radios" value="{{\App\Models\Vote::MULTIPLE_TRUE}}">
                        多选
                    </div>
                </div>
                <div class="col-sm-2">
                    <button type='button' class="btn btn-success btn-flat pull-right" onclick="appendFile()">投票选项 ＋
                    </button>
                </div>
            </div>
        @endif

        <div class="edit_file1">
            @if(isset($vote))
                @foreach($vote->items as $k=>$item)
                    <div class="file1 box box-success">
                        <div class="box-body">
                            <div class="input-group">
                                <ul id="tabs{{$k+1}}" class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tabHome{{$k+1}}" data-toggle="tab">基本信息</a>
                                    </li>
                                    <li>
                                        <a href="#tabContent{{$k+1}}" data-toggle="tab">正文</a>
                                    </li>
                                    <li>
                                        <a href="#tabImages{{$k+1}}" data-toggle="tab">图集</a>
                                    </li>
                                </ul>
                                <span class="input-group-addon files_del"
                                      style="border-left: 1px solid #d2d6de;cursor: pointer;"><span
                                            class="glyphicon glyphicon-remove"></span></span>
                            </div>
                            <div id="tabContents{{$k+1}}" class="tab-content">
                                <div id="tabHome{{$k+1}}" class="tab-pane fade in active padding-t-15">
                                    <div class="form-group">
                                        <input type="hidden" name="item_id[]" value="{{$item->id}}">
                                        <label class="control-label col-sm-1">投票选项({{$k+1}}):</label>
                                        <div class="col-sm-11">
                                            <input type="text" id="item_title{{$k+1}}" class="form-control "
                                                   value="{{$item->title}}"
                                                   name="item_title[]">
                                        </div>
                                    </div>
                                    <div class="form-group" data-id="{{$item->id}}">
                                        <label class="control-label col-sm-1">选项图片地址({{$k+1}}):</label>
                                        <div class="col-sm-5">
                                            <input type="text" id="item_url{{$k+1}}" class="form-control pull-left"
                                                   value="{{$item->image_url}}"
                                                   name="item_url[]" style="width: 85%;">
                                            <button type="button" class="btn btn-success pull-right" data-toggle="modal"
                                                    data-target="#img_preview" id="this_preview{{$k+1}}"
                                                    style="width: 14%;">预览
                                            </button>
                                        </div>

                                        <label for="item_file" class="control-label col-sm-1">投票选项图片({{$k+1}}):</label>
                                        <div class=" col-sm-5">
                                            <input id="items_file{{$k+1}}" name="item_file" type="file" class="file"
                                                   data-preview-file-type="text"
                                                   data-upload-url="/admin/files/upload?width=640" data-show-preview="false">
                                        </div>
                                    </div>
                                </div>
                                <div id="tabContent{{$k+1}}" class="tab-pane fade padding-t-15">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                        <textarea name="descriptions[]"
                                                  id="description{{$k+1}}">{{ $item->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="tabImages{{$k+1}}" class="tab-pane fade padding-t-15">
                                    <div class="form-group">
                                        <div class="col-sm-offset-1 col-sm-12">
                                            <input type="hidden" name="image_urls[]" id="image_urls{{$k+1}}"
                                                   class="form-control"
                                                   value="{{ $item->image_urls }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image_file" class="control-label col-sm-1">上传图集:</label>
                                        <div class=" col-sm-11">
                                            <input id="image_files{{$k+1}}" name="image_files[]" type="file"
                                                   data-preview-file-type="image"
                                                   data-upload-url="/admin/files/upload?width=640" multiple
                                                   class="file-loading">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        var this_url = $('#item_url' + '{{$k+1}}').val();
                        var image_items = [];

                        if (this_url == null || this_url.length > 0) {
                            image_items = ['<img height="240" src="' + this_url + '">'];
                        }

                        $('#items_file' + '{{$k+1}}').fileinput({
                            language: 'zh',
                            uploadExtraData: {_token: '{{ csrf_token() }}'},
                            allowedFileExtensions: ['jpg', 'gif', 'png'],
                            initialPreview: image_items,
                            maxFileSize: 10240,
                            browseClass: 'btn btn-success',
                            browseIcon: '<i class=\"glyphicon glyphicon-picture\"></i>',
                            removeClass: "btn btn-danger",
                            removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
                            uploadClass: "btn btn-info",
                            uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>',
                        });

                        $('#items_file' + '{{$k+1}}').on('fileuploaded', function (event, data) {
                            $('#item_url' + '{{$k+1}}').val(data.response.data);
                        });

                        //图集
                        var image_urls{{$k+1}} = $('#image_urls' + '{{$k+1}}').val();
                        var images{{$k+1}} = new Array();
                        var images_preview{{$k+1}} = [];
                        var images_config{{$k+1}} = [];

                        @if($item->get_image_urls())
                         @foreach($item->get_image_urls() as $key => $url)
                            images_preview{{$k+1}}.push('<img height="240" src="{{ $url }}" class="kv-preview-data file-preview-image">');
                        images_config{{$k+1}}.push({key: '{{ $key }}', image_url: '{{ $url }}'});
                        images{{$k+1}}['{{ $key }}'] = '{{ $url }}';
                        @endforeach
                    @endif

                     $('#image_files' + '{{$k+1}}').fileinput({
                            language: 'zh',
                            uploadExtraData: {_token: '{{ csrf_token() }}'},
                            allowedFileExtensions: ['jpg', 'gif', 'png'],
                            maxFileSize: 10240,
                            initialPreview: images_preview{{$k+1}},
                            initialPreviewAsData: false,
                            initialPreviewConfig: images_config{{$k+1}},
                            previewFileType: 'image',
                            overwriteInitial: false,
                            deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
                            browseClass: 'btn btn-success',
                            browseIcon: '<i class=\"glyphicon glyphicon-picture\"></i>',
                            removeClass: "btn btn-danger",
                            removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
                            uploadClass: "btn btn-info",
                            uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>',
                            fileActionSettings: {
                                showZoom: false
                            },
                        }).on('fileuploaded', function (event, data) {
                            if (image_urls{{$k+1}}.length > 0) {
                                image_urls{{$k+1}} += ',';
                            }
                            image_urls{{$k+1}} += data.response.data;
                            $('#image_urls' + '{{$k+1}}').val(image_urls{{$k+1}});
                        }).on('filedeleted', function (event, key) {
                            delete images{{$k+1}}[key];
                            image_urls{{$k+1}} = '';
                            for (var index in images{{$k+1}}) {
                                if (image_urls{{$k+1}}.length > 0) {
                                    image_urls{{$k+1}} += ',';
                                }
                                image_urls{{$k+1}} += images{{$k+1}}[index];
                            }
                            $('#image_urls' + '{{$k+1}}').val(image_urls{{$k+1}});
                        });

                        $('#this_preview' + '{{$k+1}}').click(function () {
                            var url = $('#item_url' + '{{$k+1}}').val();
                            imgPreview(url);
                        });

                        CKEDITOR.replace('description{{$k+1}}', {
                            height: 240,
                            filebrowserUploadUrl: '{{ url('/admin/files/upload') }}?_token={{csrf_token()}}',
                        });
                    </script>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div class="box-footer">
    <a href="/admin/votes" type="button" class="btn btn-default">取　消</a>
    <button type="submit" class="btn btn-info pull-right submit">保　存
    </button>
</div>

<script>
    $(function () {
        $('#begin_date').datetimepicker({
            format: 'YYYY/MM/DD HH:mm',
            locale: 'zh-cn'
        });
        $('#end_date').datetimepicker({
            format: 'YYYY/MM/DD HH:mm',
            locale: 'zh-cn'
        });
    });

    $(document).ready(function () {
        CKEDITOR.replace('description', {
            height: 300,
            filebrowserUploadUrl: '{{ url('/admin/files/upload') }}?_token={{csrf_token()}}',
        });
    });

    function showLink(type, is_edit) {
        if (type == '{{\App\Models\Vote::LINK_TYPE_NONE}}') {
            $('#link').html('');
        } else if (type == '{{\App\Models\Vote::LINK_TYPE_WEB}}') {
            $('#link').html('{!! Form::text('link', null, ['class' => 'form-control','id'=>'text']) !!}');
            if (is_edit == true) {
                $('#text').val('');
            }
        }
    }

    @if(isset($vote))
      showLink('{{ $vote->link_type }}', false);
    @endif

    function toastrs(message) {
        toastr.options = {
            'closeButton': true,
            'positionClass': 'toast-bottom-right',
        };
        toastr['warning'](message);
        return false;
    }

    //上传图片
    var image_url = $('#image_url').val();
    var images = [];

    if (image_url == null || image_url.length > 0) {
        images = ['<img height="240" src="' + image_url + '">'];
    }

    $('#image_file').fileinput({
        language: 'zh',
        uploadExtraData: {_token: '{{ csrf_token() }}'},
        allowedFileExtensions: ['jpg', 'gif', 'png'],
        initialPreview: images,
        maxFileSize: 10240,
        browseIcon: '<i class=\"glyphicon glyphicon-picture\"></i>',
        removeClass: "btn btn-danger",
        removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
        uploadClass: "btn btn-info",
        uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>',
    });

    $('#image_file').on('fileuploaded', function (event, data) {
        $('#image_url').val(data.response.data);
    });

    //增加文件
    var i = $(".file1").length;
    function appendFile() {
        var n = i + 1;
        i++;

        //图集
        var image_urls_html = '<div id="tabImages' + n + '" class="tab-pane fade padding-t-15">' +
                '<div class="form-group"><div class="col-sm-offset-1 col-sm-12">' +
                '<input type="hidden" name="image_urls[]" id="image_urls' + n + '" class="form-control" value="" />' +
                '</div></div><div class="form-group"><label for="image_file" class="control-label col-sm-1">上传图集:</label><div class=" col-sm-11">' +
                '<input id="image_files' + n + '" name="image_files[]" type="file" data-preview-file-type="image" data-upload-url="/admin/files/upload?width=640" multiple class="file-loading">' +
                '</div></div></div>';

        var html = '<div class="file1 box box-success"><div class="box-body">' +
                '<div class="input-group"><ul id="tabs" class="nav nav-tabs">' +
                '<li class="active"><a href="#tabHome' + n + '" data-toggle="tab">基本信息</a></li>' +
                '<li><a href="#tabContent' + n + '" data-toggle="tab">正文</a></li>' +
                '<li><a href="#tabImages' + n + '" data-toggle="tab">图集</a></li></ul>' +
                '<span class="input-group-addon files_del" style="border-left: 1px solid #d2d6de;cursor: pointer;">' +
                '<span class="glyphicon glyphicon-remove"></span></span></div>' +
                '<div id="tabContents' + n + '" class="tab-content">' +
                '<div id="tabHome' + n + '" class="tab-pane fade in active padding-t-15">' +
                '<div class="form-group">' +
                '<label class="control-label col-sm-1">投票选项(' + n + '):</label>' +
                '<div class="col-sm-11">' +
                '<input type="text" id="item_title' + n + '" class="form-control" name="item_title[]">' +
                '</div></div>' +
                '<div class="form-group"><label class="control-label col-sm-1">选项图片地址(' + n + '):</label>' +
                '<div class="col-sm-5">' +
                '<input type="text" name="item_url[]" value="" class="form-control pull-left"  id="item_url' + n + '"  style="width: 85%;">' +
                '<button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#img_preview" id="this_preview' + n + '" style="width: 14%;">预览 </button></div>' +
                '<label for="item_file" class="control-label col-sm-1">上传选项图片(' + n + '):</label>' +
                '<div class=" col-sm-5">' +
                '<input id="item_files' + n + '" name="item_file" type="file" class="file " data-preview-file-type="text" data-upload-url="/admin/files/upload?width=640" data-show-preview="false"> ' +
                '</div></div></div>' +
                '<div id="tabContent' + n + '" class="tab-pane fade padding-t-15">' +
                '<div class="form-group"><div class="col-sm-12">' +
                '<textarea name="descriptions[]" id="description' + n + '"></textarea>' +
                '</div></div></div>' + image_urls_html + '</div></div></div>';
        $(".edit_file1").append(html);

        var this_url = $('#item_url' + n).val();
        var image_items = [];

        if (this_url == null || this_url.length > 0) {
            image_items = ['<img height="240" src="' + this_url + '">'];
        }

        $('#item_files' + n).fileinput({
            language: 'zh',
            uploadExtraData: {_token: '{{ csrf_token() }}'},
            allowedFileExtensions: ['jpg', 'gif', 'png'],
            initialPreview: image_items,
            maxFileSize: 10240,
            browseClass: 'btn btn-success',
            browseIcon: '<i class=\"glyphicon glyphicon-picture\"></i>',
            removeClass: "btn btn-danger",
            removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
            uploadClass: "btn btn-info",
            uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>',
        });

        $('#item_files' + n).on('fileuploaded', function (event, data) {
            $('#item_url' + n).val(data.response.data);
        });

        //图集
        var image_urls = $('#image_urls' + n).val();
        var images = new Array();
        var images_preview = [];
        var images_config = [];

        $('#image_files' + n).fileinput({
            language: 'zh',
            uploadExtraData: {_token: '{{ csrf_token() }}'},
            allowedFileExtensions: ['jpg', 'gif', 'png'],
            maxFileSize: 10240,
            initialPreview: images_preview,
            initialPreviewAsData: false,
            initialPreviewConfig: images_config,
            previewFileType: 'image',
            overwriteInitial: false,
            deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
            browseClass: 'btn btn-success',
            browseIcon: '<i class=\"glyphicon glyphicon-picture\"></i>',
            removeClass: "btn btn-danger",
            removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
            uploadClass: "btn btn-info",
            uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>',
            fileActionSettings: {
                showZoom: false
            },
        }).on('fileuploaded', function (event, data) {
            if (image_urls.length > 0) {
                image_urls += ',';
            }
            image_urls += data.response.data;
            $('#image_urls' + n).val(image_urls);
        }).on('filedeleted', function (event, key) {
            delete images[key];
            image_urls = '';
            for (var index in images) {
                if (image_urls.length > 0) {
                    image_urls += ',';
                }
                image_urls += images[index];
            }
            $('#image_urls' + n).val(image_urls);
        });

        //添加页上传某个选项的图片后 预览
        $('#this_preview' + n).click(function () {
            var url = $('#item_url' + n).val();
            imgPreview(url);
        });

        CKEDITOR.replace('description' + n, {
            height: 240,
            filebrowserUploadUrl: '{{ url('/admin/files/upload') }}?_token={{csrf_token()}}',
        });
    }
    @if(!isset($vote))
        appendFile();
    @endif

    $('.submit').click(function () {
        var cur_num = $(".file1").length;
        var image_file = $('#image_file').fileinput('getFileStack');
        if (image_file.length > 0) {
            return toastrs('请先上传图片!');
        }

        for (var i = 1; i <= cur_num; i++) {
            items_file = $('#items_file' + i).fileinput('getFileStack');
            image_files = $('#image_files' + i).fileinput('getFileStack');
            if (items_file.length > 0) {
                return toastrs('投票选项图片（' + i + '），未上传图片');
            }
            if (image_files.length > 0) {
                return toastrs('选项图集（' + i + '），未上传图片');
            }
        }

    });

    $('#tabContent').delegate('.files_del', 'click', function () {
        var cur_num = $(".file1").length;
        if (cur_num < 2) {
            return toastrs('投票选项最少有一个');
        } else {
            $(this).parents('div.file1').remove();
        }
    })

    function imgPreview(url) {
        $('#contents').html('<img src="' + url + '" />');
    }
</script>