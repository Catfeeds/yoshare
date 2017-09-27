<div class="col-xs-12 no-padding">
    <ul id="tabs" class="nav nav-tabs tabs col-sm-12 pull-left no-padding subject">
        <li class="active">
            <a href="#tabHome" data-toggle="tab">基本信息</a>
        </li>
        <li>
            <a href="#tabContent" data-toggle="tab">正文</a>
        </li>

        @if(isset($survey))
            @foreach($survey->subjects as $k=>$item_subject)
                <li class="tab_subjects_item">
                    <a href="#tabSubjectsItems{{ $k+1 }}" data-toggle="tab">问卷题目{{ $k+1 }}</a>
                </li>
            @endforeach
        @else
            <li class="tab_subjects_item">
                <a href="#tabSubjectsItems1" data-toggle="tab">问卷题目1</a>
            </li>
        @endif

    </ul>
    <ul style="list-style:none;">
        <li class="pull-right">
            <button type='button' class="btn btn-success btn-flat pull-right" onclick="appendSubject()">问卷题目 ＋
            </button>
        </li>
    </ul>
</div>
<div id="tabContents" class="col-xs-12 tab-content no-padding subject_content">
    <div id="tabHome" class="tab-pane fade in active padding-t-15">
        <div class="form-group">
            <label class="col-sm-1 control-label">标题</label>
            <div class="col-sm-11">
                {!! Form::text('title', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">问卷类型:</label>
            <div class="col-sm-5">
                {!! Form::select('multiple', \App\Models\Survey::MULTIPLE, null, ['class' => 'form-control']) !!}
            </div>

            {!! Form::label('link', '外链:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-1">
                {!! Form::select('link_type', \App\Models\Survey::getLinkTypes(), null, ['class' => 'form-control','onchange'=>'return showLink(this.value,true)']) !!}
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
                {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    @if(isset($survey))
        @foreach($subject as $k=>$item_subject)
            <div id="tabSubjectsItems{{$k+1}}" class="tab-pane fade padding-t-15 tab_subjects">
                <div class="edit_file{{$k+1}}">
                    <div class="file1 panel panel-default">
                        <div class="box-body">
                            <div class="input-group">
                                <ul id="tabs{{$k+1}}" class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tabHome{{$k+1}}" data-toggle="tab"><label
                                                    class="no-margin">问卷题目</label></a>
                                    </li>
                                </ul>
                                <span class="input-group-addon files_del"
                                      style="border-left: 1px solid #d2d6de;cursor: pointer;"><span
                                            class="glyphicon glyphicon-remove"></span></span>
                            </div>
                            <div id="tabSubjects{{$k+1}}" class="tab-content">
                                <div id="tabHome{{$k+1}}" class="tab-pane fade in active padding-t-15">
                                    <div class="col-sm-8 pull-left" style="padding-left: 0;">
                                        <div class="form-group">
                                            <input type="hidden" name="item_id_subject[]"
                                                   value="{{$item_subject->id}}">
                                            <div class="col-sm-12">
                                                <input type="text" id="item_subject{{$k+1}}"
                                                       class="form-control "
                                                       value="{{$item_subject->title}}"
                                                       name="item_subject[]" placeholder="输入标题">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <textarea name="summary_subject[]" class="col-sm-12 form-control"
                                                          rows="11"
                                                          placeholder="输入描述"
                                                          id="summary{{$k+1}}">{{ $item_subject->summary }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pull-right" data-id="{{$item_subject->id}}"
                                         style="padding-right: 0;">
                                        <div class="col-sm-12">
                                            <input name="item_url_subject[]" id="item_url_subject{{$k+1}}"
                                                   type="hidden"
                                                   value="{{$item_subject->url}}">
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <input id="items_file_subject{{$k+1}}" name="item_file_subject"
                                                       type="file" class="file"
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
                        var this_url = $('#item_url_subject{{$k+1}}').val();
                        var image_items = [];

                        if (this_url == null || this_url.length > 0) {
                            image_items = ['<img height="200" src="' + this_url + '" class="thumb">'];
                        }

                        $("#items_file_subject{{$k+1}}").fileinput({
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
                            $('#item_url_subject{{$k+1}}').val(data.response.data);
                        }).on('filedeleted', function (event, key) {
                            $('#item_url_subject{{$k+1}}').val('');
                        });
                    </script>
                    @foreach($item_subject->items as $item_k=>$item)
                        @if($item_subject->id == $item->refer_id)
                            <div class="file1 panel panel-default">
                                <div class="box-body">
                                    <div class="input-group">
                                        <ul id="tabs{{$item_k+1}}" class="nav nav-tabs">
                                            <li class="active">
                                                <a href="#tabHome{{$item_k+1}}" data-toggle="tab"><label
                                                            class="no-margin">问卷选项({{$item_k+1}})</label></a>
                                            </li>
                                            <span class="pull-right">
                                                 <button type="button" class="btn btn-success btn-flatpull-right "
                                                         onclick="appendFile()">问卷选项 ＋
                                            </button></span>
                                        </ul>
                                        <span class="input-group-addon files_del"
                                              style="border-left: 1px solid #d2d6de;cursor: pointer;"><span
                                                    class="glyphicon glyphicon-remove"></span></span>
                                    </div>
                                    <div id="tabItems{{$item_k+1}}" class="tab-content">
                                        <div id="tabHome{{$item_k+1}}"
                                             class="tab-pane fade in active padding-t-15">
                                            <div class="col-sm-8 pull-left" style="padding-left: 0;">
                                                <div class="form-group">
                                                    <input type="hidden" name="item_id{{$k+1}}[]"
                                                           value="{{$item->id}}">
                                                    <div class="col-sm-12">
                                                        <input type="text" id="item_title{{$item_k+1}}"
                                                               class="form-control "
                                                               value="{{$item->title}}"
                                                               name="item_title{{$k+1}}[]" placeholder="输入标题">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                <textarea name="summary{{$k+1}}[]" class="col-sm-12 form-control"
                                                          rows="11"
                                                          placeholder="输入描述"
                                                          id="summary{{$item_k+1}}">{{ $item->summary }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 pull-right" data-id="{{$item->id}}"
                                                 style="padding-right: 0;">
                                                <div class="col-sm-12">
                                                    <input name="item_url{{$k+1}}[]"
                                                           id="item_url_{{$item->refer_id}}_{{ $item_k+1 }}"
                                                           type="hidden"
                                                           value="{{$item->url}}">
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <input id="items_file_{{$item->refer_id}}_{{$item_k+1}}"
                                                               name="item_file{{$k+1}}" type="file"
                                                               class="file"
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
                                var this_url = $('#item_url_{{$item->refer_id}}_{{$item_k+1}}').val();
                                var image_items = [];

                                if (this_url == null || this_url.length > 0) {
                                    image_items = ['<img height="200" src="' + this_url + '" class="thumb">'];
                                }

                                $("#items_file_{{$item->refer_id}}_{{ $item_k+1 }}").fileinput({
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
                                    $('#item_url_{{$item->refer_id}}_{{$item_k+1}}').val(data.response.data);
                                }).on('filedeleted', function (event, key) {
                                    $('#item_url_{{$item->refer_id}}_{{$item_k+1}}').val('');
                                });
                            </script>
                        @endif

                    @endforeach

                </div>
            </div>
        @endforeach
    @else
        <div id="tabSubjectsItems1" class="tab-pane fade padding-t-15 tab_subjects">
            <div class="edit_file1">
                <div class="file1 panel panel-default">
                    <div class="box-body">
                        <div class="input-group">
                            <ul id="tabs1" class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tabHome1" data-toggle="tab"><label
                                                class="no-margin">问卷题目</label></a>
                                </li>
                                <span class="pull-right">
                                                 <button type="button" class="btn btn-success btn-flatpull-right "
                                                         onclick="appendFile()">问卷选项 ＋
                                            </button></span>
                            </ul>
                            <span class="input-group-addon files_del"
                                  style="border-left: 1px solid #d2d6de;cursor: pointer;"><span
                                        class="glyphicon glyphicon-remove"></span></span>
                        </div>
                        <div id="tabItems1" class="tab-content">
                            <div id="tabHome1" class="tab-pane fade in active padding-t-15">
                                <div class="col-sm-8 pull-left" style="padding-left: 0;">
                                    <div class="form-group">
                                        <input type="hidden" name="item_subject_id[]" value="">
                                        <div class="col-sm-12">
                                            <input type="text" id="item_subject" class="form-control"
                                                   value=""
                                                   name="item_subject[]" placeholder="输入标题">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                                <textarea name="summary_subject[]" class="col-sm-12 form-control"
                                                          rows="11"
                                                          placeholder="输入描述"
                                                          id="summary_subject"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 pull-right" data-id=""
                                     style="padding-right: 0;">
                                    <div class="col-sm-12">
                                        <input name="item_url_subject[]" id="item_url_subject1" type="hidden"
                                               value="">
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input id="items_file_subject1" name="item_file_subject" type="file"
                                                   class="file"
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
                    var this_url = $('#item_url_subject1').val();
                    var image_items = [];

                    if (this_url == null || this_url.length > 0) {
                        image_items = ['<img height="200" src="' + this_url + '" class="thumb">'];
                    }

                    $('#items_file_subject1').fileinput({
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
                        $('#item_url_subject1').val(data.response.data);
                    }).on('filedeleted', function (event, key) {
                        $('#item_url_subject1').val('');
                    });
                </script>
            </div>

        </div>
    @endif


</div>

<div class="col-xs-12 box-footer">
    <a href="/admin/surveys" type="button" class="btn btn-default">取　消</a>
    <button type="submit" class="btn btn-info pull-right submit">保　存
    </button>
</div>

<style>
    .kv-file-content .thumb, .kv-file-content .file-preview-image {
        height: 130px !important;
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
        CKEDITOR.replace('description', {
            extraPlugins: 'uploadimage,image2',
            height: 500,
            filebrowserUploadUrl: '{{ url('/admin/files/upload?type=image') }}?_token={{ csrf_token()}}',
            contentsCss: [CKEDITOR.basePath + 'contents.css', '/css/admin/app.css'],
            image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
            image2_disableResizer: true
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

    @if(isset($survey))
        showLink('{{ $survey->link_type }}', false);
            @endif

    var image_url = $('#image_url').val();
    var images = [];

    if (image_url == null || image_url.length > 0) {
        images = ['<img height="200" src="' + image_url + '">'];
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


    var i = $(".subject_items").length;
    function appendFile() {
        var sub = $('.tab_subjects').length; //use subject
        var n = i;
        i++;

        var html =
            '<div class="file1 panel panel-default subject_items">' +
            '<div class="box-body"><div class="input-group"><ul id="tabs' + n + '" class="nav nav-tabs">' +
            '<li class="active"><a href="#tabHome' + n + '" data-toggle="tab">' +
            '<label class="no-margin">问卷选项(' + n + ')</label></a></li>' +
            '<span class="pull-right">' +
            '<button type="button" class="btn btn-success btn-flatpull-right " onclick="appendFile()">问卷选项 ＋ ' +
            '</button></span>' +
            '</ul>' +
            '<span class="input-group-addon files_del" style="border-left: 1px solid #d2d6de;cursor: pointer;">' +
            '<span class="glyphicon glyphicon-remove"></span></span></div>' +
            '<div id="tabItems' + n + '" class="tab-content">' +
            '<div id="tabHome' + n + '" class="tab-pane fade in active padding-t-15">' +
            '<div class="col-sm-8 pull-left" style="padding-left: 0;">' +
            '<div class="form-group"><div class="col-sm-12">' +
            '<input type="text" id="item_title' + n + '" class="form-control " value="" name="item_title' + sub + '[]" placeholder="输入标题"></div></div>' +
            '<div class="form-group"><div class="col-sm-12">' +
            '<textarea name="summary' + sub + '[]" class="col-sm-12 form-control" rows="11" placeholder="输入描述" id="summary' + n + '"></textarea></div></div></div> ' +
            '<div class="col-sm-4 pull-right" style="padding-right: 0;"><div class="col-sm-12"> ' +
            '<input name="item_url' + sub + '[]" id="item_url' + n + '"  type="hidden" value=""></div> ' +
            '<div class="form-group"><div class="col-sm-12">' +
            '<input id="item_file' + n + '" name="item_file' + sub + '" type="file" class="file" data-preview-file-type="text" data-upload-url="/admin/files/upload?type=image">' +
            '</div></div></div></div></div></div></div>';

        if (sub == 1) {
            $(".edit_file1").append(html);
        } else {
            $(".edit_file" + sub).append(html);
        }


        var this_url = $('#item_url' + n).val();
        var image_items = [];

        if (this_url == null || this_url.length > 0) {
            image_items = ['<img height="200" src="' + this_url + '">'];
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
    }


    @if(!isset($survey))
        appendFile();
    @endif


    // 题目的追加
    var j = $('.tab_subjects').length;

    function appendSubject() {

        var sub = $('.tab_subjects').length; //use subject
        var n = j + 1;
//        var n =j;
        j++;

        var html = '<li class="tab_subjects_item">' +
            '<a href="#tabSubjectsItems' + n + '" data-toggle="tab">问卷题目' + n + '</a>' +
            '</li>';

        var subject_content =
            '<div id="tabSubjectsItems' + n + '" class="tab-pane fade padding-t-15 tab_subjects">' +
            '<div class="edit_file' + n + '">' +
            '<div class="file1 panel panel-default">' +

            '<div class="box-body"><div class="input-group"><ul id="tabs' + n + '" class="nav nav-tabs">' +
            '<li class="active"><a href="#tabHome' + n + '" data-toggle="tab">' +
            '<label class="no-margin">问卷题目</label></a></li>' +
            '</ul>' +
            '<span class="input-group-addon files_del" style="border-left: 1px solid #d2d6de;cursor: pointer;">' +
            '<span class="glyphicon glyphicon-remove"></span></span></div>' +
            '<div id="tabSubjects' + n + '" class="tab-content">' +
            '<div id="tabHome' + n + '" class="tab-pane fade in active padding-t-15">' +
            '<div class="col-sm-8 pull-left" style="padding-left: 0;">' +
            '<div class="form-group">' +
            '<input type="hidden" name="item_id_subject[]" value="">' +
            '<div class="col-sm-12">' +
            '<input type="text" id="item_subject' + n + '" class="form-control " value="" name="item_subject[]" placeholder="输入标题"></div></div>' +
            '<div class="form-group"><div class="col-sm-12">' +
            '<textarea name="summary_subject[]" class="col-sm-12 form-control" rows="11" placeholder="输入描述" id="summary_subject' + n + '"></textarea></div></div></div> ' +
            '<div class="col-sm-4 pull-right" style="padding-right: 0;"><div class="col-sm-12"> ' +
            '<input name="item_url_subject[]" id="item_url_subject' + n + '"  type="hidden" value=""></div> ' +
            '<div class="form-group"><div class="col-sm-12">' +
            '<input id="item_file_subject' + n + '" name="item_file_subject" type="file" class="file" data-preview-file-type="text" data-upload-url="/admin/files/upload?type=image">' +
            '</div></div></div></div></div></div></div>' +

            '<div class="file1 panel panel-default subject_items">' +
            '<div class="box-body"><div class="input-group"><ul id="tabs' + (n - 1) + '" class="nav nav-tabs">' +
            '<li class="active"><a href="#tabHome' + (n - 1) + '" data-toggle="tab">' +
            '<label class="no-margin">问卷选项(' + (n - 1) + ')</label></a></li>' +
            '<span class="pull-right">' +
            '<button type="button" class="btn btn-success btn-flatpull-right " onclick="appendFile()">问卷选项 ＋ ' +
            '</button></span>' +
            '</ul>' +
            '<span class="input-group-addon files_del" style="border-left: 1px solid #d2d6de;cursor: pointer;">' +
            '<span class="glyphicon glyphicon-remove"></span></span></div>' +
            '<div id="tabItems' + (n - 1) + '" class="tab-content">' +
            '<div id="tabHome' + (n - 1) + '" class="tab-pane fade in active padding-t-15">' +
            '<div class="col-sm-8 pull-left" style="padding-left: 0;">' +
            '<div class="form-group">' +
            '<input type="hidden" name="item_id' + (n) + '[]" value="">' +
            '<div class="col-sm-12">' +
            '<input type="text" id="item_title' + (n - 1) + '" class="form-control " value="" name="item_title' + (n) + '[]" placeholder="输入标题"></div></div>' +
            '<div class="form-group"><div class="col-sm-12">' +
            '<textarea name="summary' + (n) + '[]" class="col-sm-12 form-control" rows="11" placeholder="输入描述" id="summary' + (n - 1) + '"></textarea></div></div></div> ' +
            '<div class="col-sm-4 pull-right" style="padding-right: 0;"><div class="col-sm-12"> ' +
            '<input name="item_url' + (n) + '[]" id="item_url' + (n - 1) + '"  type="hidden" value=""></div> ' +
            '<div class="form-group"><div class="col-sm-12">' +
            '<input id="item_file_' + (n) + '_' + (n - 1) + '" name="item_file' + (sub) + '" type="file" class="file" data-preview-file-type="text" data-upload-url="/admin/files/upload?type=image">' +
            '</div></div></div></div></div></div></div></div></div>';


        $(".subject").append(html); //追加标签

        $(".subject_content").append(subject_content);       //追加内容

        var this_url = $('#item_url' + (n - 1)).val();
        var this_url_subject = $('#item_url_subject' + (n)).val();

        var image_items = [];
        var image_items_subject = [];

        if (this_url == null || this_url.length > 0) {
            image_items = ['<img height="200" src="' + this_url + '">'];
        }

        if (this_url_subject == null || this_url_subject.length > 0) {
            image_items_subject = ['<img height="200" src="' + this_url_subject + '">'];
        }

        $('#item_file_' + (n) + '_' + (n - 1)).fileinput({
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
            $('#item_url' + (n) + '_' + (n - 1)).val(data.response.data);
        }).on('filedeleted', function (event, key) {
            $('#item_url' + (n) + '_' + (n - 1)).val('');
        });

        $('#item_file_subject' + (n)).fileinput({
            language: 'zh',
            uploadExtraData: {_token: '{{ csrf_token() }}'},
            allowedFileExtensions: ['jpg', 'gif', 'png'],
            initialPreview: image_items_subject,
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
            $('#item_url_subject' + (n)).val(data.response.data);
        }).on('filedeleted', function (event, key) {
            $('#item_url_subject' + (n)).val('');
        });
    }

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

    $('#tabContents').delegate('.files_del', 'click', function () {
        var cur_num = $(".subject_items").length;
        if (cur_num < 2) {
            return toastrs('warning', '问卷选项最少有一个');
        } else {
            $(this).parents('div.subject_items').remove();
        }
    })
</script>