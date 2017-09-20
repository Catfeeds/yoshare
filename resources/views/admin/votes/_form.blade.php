<ul id="tabs" class="nav nav-tabs">
    <li class="active">
        <a href="#tabHome" data-toggle="tab">基本信息</a>
    </li>
    <li>
        <a href="#tabContent" data-toggle="tab">正文</a>
    </li>
    <li>
        <a href="#tabItems" data-toggle="tab">投票选项</a>
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
                       data-upload-url="/admin/files/upload?type=image">
            </div>
        </div>
    </div>

    <div id="tabContent" class="tab-pane fade padding-t-15">
        <div class="form-group">
            <div class="col-sm-12">
                {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <div id="tabItems" class="tab-pane fade padding-t-15">
        @if(isset($vote))
            <div class="form-group">
                <label class="col-sm-1 control-label">投票类型</label>
                <div class="col-sm-9">
                    <div class="pull-left col-sm-2 no-padding">
                        {!! Form::select('multiple', \App\Models\Vote::MULTIPLES, null, ['class' => 'form-control']) !!}
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
                <div class="col-sm-9">
                    <div class="pull-left col-sm-2 no-padding">
                        {!! Form::select('multiple', \App\Models\Vote::MULTIPLES, null, ['class' => 'form-control']) !!}
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
                                        <a href="#tabHome{{$k+1}}" data-toggle="tab"><label
                                                    class="no-margin">投票选项({{$k+1}})</label></a>
                                    </li>
                                </ul>
                                <span class="input-group-addon files_del"
                                      style="border-left: 1px solid #d2d6de;cursor: pointer;"><span
                                            class="glyphicon glyphicon-remove"></span></span>
                            </div>
                            <div id="tabItems{{$k+1}}" class="tab-content">
                                <div id="tabHome{{$k+1}}" class="tab-pane fade in active padding-t-15">
                                    <div class="col-sm-7 pull-left" style="width:65%;padding-left: 0;">
                                        <div class="form-group">
                                            <input type="hidden" name="item_id[]" value="{{$item->id}}">
                                            <div class="col-sm-12">
                                                <input type="text" id="item_title{{$k+1}}" class="form-control "
                                                       value="{{$item->title}}"
                                                       name="item_title[]" placeholder="输入标题">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <textarea name="summaries[]"
                                                          id="summary{{$k+1}}">{{ $item->summary }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5 pull-right" data-id="{{$item->id}}"
                                         style="width:35%;padding-right: 0;">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <input type="text" id="item_url{{$k+1}}" class="form-control pull-left"
                                                       value="{{$item->url}}"
                                                       name="item_url[]" placeholder="图片地址">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <input id="items_file{{$k+1}}" name="item_file" type="file" class="file"
                                                       data-preview-file-type="text"
                                                       data-upload-url="/admin/files/upload?type=image">
                                            </div>
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
                            image_items = ['<img height="240" src="' + this_url + '" class="thumb">'];
                        }

                        $('#items_file' + '{{$k+1}}').fileinput({
                            language: 'zh',
                            uploadExtraData: {_token: '{{ csrf_token() }}'},
                            allowedFileExtensions: ['jpg', 'gif', 'png'],
                            initialPreview: image_items,
                            maxFileSize: 10240,
                            initialPreviewConfig: [{key: 1}],
                            deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
                            browseClass: 'btn btn-success',
                            browseIcon: '<i class=\"glyphicon glyphicon-picture\"></i>',
                            removeClass: "btn btn-danger",
                            removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
                            uploadClass: "btn btn-info",
                            uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>',
                        }).on('fileuploaded', function (event, data) {
                            $('#item_url' + '{{$k+1}}').val(data.response.data);
                        }).on('filedeleted', function (event, key) {
                            $('#item_url' + '{{$k+1}}').val('');
                        });

                        CKEDITOR.replace('summary{{$k+1}}', {
                            height: 220,
                            filebrowserUploadUrl: '{{ url('admin/files/upload') }}?_token={{csrf_token()}}',
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

<style>
    .kv-file-content .thumb, .kv-file-content .file-preview-image {
        height: 135px !important;
    }

    .file-zoom-content .thumb {
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 100%;
    }
</style>
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
        CKEDITOR.replace('content', {
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
        initialPreviewConfig: [{key: 1}],
        deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
        browseIcon: '<i class=\"glyphicon glyphicon-picture\"></i>',
        removeClass: "btn btn-danger",
        removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
        uploadClass: "btn btn-info",
        uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>',
    }).on('fileuploaded', function (event, data) {
        $('#image_url').val(data.response.data);
    }).on('filedeleted', function (event, key) {
        $('#image_url').val('');
    });

    //增加文件
    var i = $(".file1").length;
    function appendFile() {
        var n = i + 1;
        i++;

        var html = '<div class="file1 box box-success">' +
                '<div class="box-body"><div class="input-group"><ul id="tabs' + n + '" class="nav nav-tabs">' +
                '<li class="active"><a href="#tabHome' + n + '" data-toggle="tab">' +
                '<label class="no-margin">投票选项(' + n + ')</label></a></li></ul>' +
                '<span class="input-group-addon files_del" style="border-left: 1px solid #d2d6de;cursor: pointer;">' +
                '<span class="glyphicon glyphicon-remove"></span></span></div>' +
                '<div id="tabItems' + n + '" class="tab-content">' +
                '<div id="tabHome' + n + '" class="tab-pane fade in active padding-t-15">' +
                '<div class="col-sm-7 pull-left" style="width:65%;padding-left: 0;">' +
                '<div class="form-group"><div class="col-sm-12">' +
                '<input type="text" id="item_title' + n + '" class="form-control " value="" name="item_title[]" placeholder="输入标题"></div></div>' +
                '<div class="form-group"><div class="col-sm-12"><textarea name="summaries[]" id="summary' + n + '"></textarea></div></div></div> ' +
                '<div class="col-sm-5 pull-right" style="width:35%;padding-right: 0;"><div class="form-group"><div class="col-sm-12"> ' +
                '<input type="text" id="item_url' + n + '" class="form-control pull-left" value="" name="item_url[]" placeholder="图片地址"></div></div> ' +
                '<div class="form-group"><div class="col-sm-12">' +
                '<input id="item_file' + n + '" name="item_file" type="file" class="file" data-preview-file-type="text" data-upload-url="/admin/files/upload?type=image"></div></div></div></div></div></div></div>'

        $(".edit_file1").append(html);

        var this_url = $('#item_url' + n).val();
        var image_items = [];

        if (this_url == null || this_url.length > 0) {
            image_items = ['<img height="240" src="' + this_url + '">'];
        }

        $('#item_file' + n).fileinput({
            language: 'zh',
            uploadExtraData: {_token: '{{ csrf_token() }}'},
            allowedFileExtensions: ['jpg', 'gif', 'png'],
            initialPreview: image_items,
            maxFileSize: 10240,
            initialPreviewConfig: [{key: 1}],
            deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
            browseClass: 'btn btn-success',
            browseIcon: '<i class=\"glyphicon glyphicon-picture\"></i>',
            removeClass: "btn btn-danger",
            removeIcon: '<i class=\"glyphicon glyphicon-trash\"></i>',
            uploadClass: "btn btn-info",
            uploadIcon: '<i class=\"glyphicon glyphicon-upload\"></i>',
        }).on('fileuploaded', function (event, data) {
            $('#item_url' + n).val(data.response.data);
        }).on('filedeleted', function (event, key) {
            $('#item_url' + n).val('');
        });

        CKEDITOR.replace('summary' + n, {
            height: 220,
            filebrowserUploadUrl: '{{ url('admin/files/upload') }}?_token={{csrf_token()}}',
        })
    }

    @if(!isset($vote))
        appendFile();
    @endif

     $('.submit').click(function () {
        var ret = true;
        $('.file').each(function (k, obj) {
            var files = $(this).fileinput('getFileStack');

            if (files.length > 0) {
                return ret = toastrs('warning', '请先上传文件!');
            }
        });
        return ret;
    });

    $('#tabItems').delegate('.files_del', 'click', function () {
        var cur_num = $(".file1").length;
        if (cur_num < 2) {
            return toastrs('warning', '投票选项最少有一个');
        } else {
            $(this).parents('div.file1').remove();
        }
    })
</script>