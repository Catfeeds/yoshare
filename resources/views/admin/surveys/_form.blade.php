<ul id="tabs" class="nav nav-tabs">
    <li class="active">
        <a href="#tabHome" data-toggle="tab">基本信息</a>
    </li>
    <li>
        <a href="#tabTitle" data-toggle="tab">题目</a>
    </li>
    <li>
        <a href="#tabContent" data-toggle="tab">正文</a>
    </li>

</ul>
<div id="tabContents" class="tab-content">

    <div id="tabHome" class="tab-pane fade in active padding-t-15">
        <div class="form-group">
            {!! Form::label('title', '标题:', ['class' => 'control-label col-sm-1']) !!}
            <div class="input-group col-sm-10">
                <input type="text" name="title" id="title" value="{{ !empty($survey)? $survey->title :'' }}"
                       class="form-control"
                       style="margin:0 30px 0 15px;"/>
                <span class="input-group-btn">
                      <button class="btn btn-primary btn-flat add_option_title" type="button" style="margin-left:15px;">添加题目</button>
                    </span>
                <span class="input-group-btn">
                             <button class="btn btn-primary btn-flat add_option" type="button">添加选项</button>
                    </span>
            </div>

        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">类型:</label>
            <div class="col-sm-5">
                {!! Form::select('multiple', \App\Models\Survey::MULTIPLE, null, ['class' => 'form-control']) !!}
            </div>

            {!! Form::label('link', '外链:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-5">
                {!! Form::text('link', null, ['class' => 'form-control']) !!}
            </div>
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

    @if(!empty($survey))
        @foreach($survey->titles as $title_key=>$sub_title)
            <!--题目-->
                <div class="form-group sub_title" data-id="{{$sub_title->id}}">
                    <label class="control-label col-sm-1">题目({{$title_key+1}}):</label>
                    <div class="col-sm-11">
                        <div class="input-group">
                            <input type="hidden" name="sub_title_id[]" value="{{$sub_title->id}}">
                            <input type="text" id="sub_title{{$title_key+1}}" class="form-control "
                                   value="{{$sub_title->title}}"
                                   name="sub_title[]">
                            <span class="input-group-addon sub_title_del1">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </span>
                        </div>
                    </div>
                </div>

                @foreach($survey->items as $item_key=>$item)
                    @if($sub_title->id == $item->survey_title_id)
                        <div class="form-group file" data-item-id="{{$item->survey_title_id}}">
                            <label class="control-label col-sm-1">选项:</label>
                            <div class="col-sm-11">
                                <div class="input-group">
                                    <input type="hidden" name="sub_item_id[]" value="{{$item->id."-".$item->title}}">

                                    <input type="text" id="sub_title_item_{{$title_key+1}}" class="form-control"
                                           value="{{$item->title}}"
                                           name="sub_title_item_{{$title_key+1}}[]">
                                    <span class="input-group-addon   file_del1">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </span>

                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        @else
            <div class="form-group sub_title">
                <label class="control-label col-sm-1">题目(1):</label>
                <div class="col-sm-11">
                    <div class="input-group">
                        <input type="text" id="sub_title" class="form-control" value=""
                               name="sub_title[]">
                        <span class="input-group-addon sub_title_del2">
                            <span class="glyphicon glyphicon-remove"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group file">
                {{--<label class="control-label col-sm-1">选项(1):</label>--}}
                <label class="control-label col-sm-1">选项:</label>
                <div class="col-sm-11">
                    <div class="input-group">
                        <input type="text" id="sub_title_item_1" class="form-control" value=""
                               name="sub_title_item_1[]">
                        <span class="input-group-addon file_del2">
                    <span class="glyphicon glyphicon-remove"></span>
                </span>
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group">
            {!! Form::label('image_url', '图片地址:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-11">
                {!! Form::text('image_url', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="image_file" class="control-label col-sm-1">上传图片:</label>
            <div class="col-sm-11">
                <input id="image_file" name="image_file" type="file" data-preview-file-type="text"
                       data-upload-url="/admin/files/upload?type=image"/>
            </div>
        </div>
    </div>

    <div id="tabTitle" class="tab-pane fade padding-t-15">

        {{--<div class="col-sm-6">--}}
            {{--<div class="form-group">--}}
                {{--{!! Form::label('title', '标题:', ['class' => 'control-label col-sm-2']) !!}--}}
                {{--<div class="input-group col-sm-10">--}}
                    {{--<input type="text" name="title" id="title" value="{{ !empty($survey)? $survey->title :'' }}"--}}
                           {{--class="form-control"--}}
                           {{--style="margin:0 30px 0 15px;"/>--}}
                    {{--<span class="input-group-btn">--}}
                             {{--<button class="btn btn-primary btn-flat add_option" type="button">添加选项</button>--}}
                    {{--</span>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group">--}}
                {{--{!! Form::label('title', '标题描述:', ['class' => 'control-label col-sm-2']) !!}--}}
                {{--<div class="input-group col-sm-10">--}}
                    {{--<textarea class="form-control" name="" id="" cols="10" rows="10"--}}
                              {{--style="margin:0 30px 0 15px;"></textarea>--}}
                {{--</div>--}}
            {{--</div>--}}


        {{--</div>--}}

        {{--<div class="col-sm-6 form-group">--}}
            {{--<div class="form-group">--}}
                {{--{!! Form::label('title_url', '图片地址:', ['class' => 'control-label col-sm-2']) !!}--}}
                {{--<div class="col-sm-10">--}}
                    {{--{!! Form::text('', null, ['class' => 'form-control']) !!}--}}
                    {{--<input type="text" class="form-control title_url">--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--<label for="image_title" class="control-label col-sm-2">上传图片:</label>--}}
                {{--<div class="col-sm-10">--}}
                    {{--<input class="image_title" name="image_title" type="file" data-preview-file-type="text"--}}
                           {{--data-upload-url="/admin/files/upload?type=image"/>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="form-group">
            {!! Form::label('title_url', '图片地址:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-11">
{{--                {!! Form::text('', null, ['class' => 'form-control']) !!}--}}
                <input type="text" class="form-control title_url">
            </div>
        </div>
        <div class="form-group">
            <label for="image_title" class="control-label col-sm-1">上传图片:</label>
            <div class="col-sm-11">
                <input class="image_title" name="image_title" type="file" data-preview-file-type="text"
                       data-upload-url="/admin/files/upload?type=image"/>
            </div>
        </div>

        {{--<div class="col-sm-3">--}}
            {{--<div class="form-group">--}}
                {{--{!! Form::label('title', '选项标题:', ['class' => 'control-label col-sm-3']) !!}--}}
                {{--<div class="input-group col-sm-8">--}}
                    {{--<input type="text" name="title" id="title" value="{{ !empty($survey)? $survey->title :'' }}"--}}
                           {{--class="form-control"--}}
                           {{--style="margin:0 30px 0 15px;"/>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group">--}}
                {{--{!! Form::label('title', '选项描述:', ['class' => 'control-label col-sm-3']) !!}--}}
                {{--<div class="input-group col-sm-8">--}}
                    {{--<textarea class="form-control" name="" id="" cols="10" rows="10"--}}
                              {{--style="margin:0 30px 0 15px;"></textarea>--}}
                {{--</div>--}}
            {{--</div>--}}


        {{--</div>--}}

        {{--<div class="col-sm-3 form-group">--}}
            {{--<div class="form-group">--}}
                {{--{!! Form::label('title_url_1', '图片地址:', ['class' => 'control-label col-sm-3']) !!}--}}
                {{--<div class="col-sm-9">--}}
                    {{--<input type="text" class="form-control title_url_1">--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--<label for="image_title" class="control-label col-sm-3">上传图片:</label>--}}
                {{--<div class="col-sm-12">--}}
                    {{--<input class="image_title" name="image_title" type="file" data-preview-file-type="text"--}}
                           {{--data-upload-url="/admin/files/upload?type=image"/>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}

        {{--<div class="col-sm-3">--}}
            {{--<div class="form-group">--}}
                {{--{!! Form::label('title', '选项标题:', ['class' => 'control-label col-sm-3']) !!}--}}
                {{--<div class="input-group col-sm-8">--}}
                    {{--<input type="text" name="title" id="title" value="{{ !empty($survey)? $survey->title :'' }}"--}}
                           {{--class="form-control"--}}
                           {{--style="margin:0 30px 0 15px;"/>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group">--}}
                {{--{!! Form::label('title', '选项描述:', ['class' => 'control-label col-sm-3']) !!}--}}
                {{--<div class="input-group col-sm-8">--}}
                    {{--<textarea class="form-control" name="" id="" cols="10" rows="10"--}}
                              {{--style="margin:0 30px 0 15px;"></textarea>--}}
                {{--</div>--}}
            {{--</div>--}}


        {{--</div>--}}

        {{--<div class="col-sm-3 form-group">--}}
            {{--<div class="form-group">--}}
                {{--{!! Form::label('title_url_2', '图片地址:', ['class' => 'control-label col-sm-3']) !!}--}}
                {{--<div class="col-sm-9">--}}
                    {{--<input type="text" class="form-control title_url_2">--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--<label for="image_title" class="control-label col-sm-3">上传图片:</label>--}}
                {{--<div class="col-sm-9">--}}
                    {{--<input class="image_title" name="image_title" type="file" data-preview-file-type="text"--}}
                           {{--data-upload-url="/admin/files/upload?type=image"/>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

    </div>

    <div id="tabContent" class="tab-pane fade padding-t-15">
        <div class="form-group">
            <div class="col-sm-12">
                {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

</div>

<div class="box-footer">
    <button type="button" class="btn btn-default" onclick="location.href='/admin/surveys';">取　消</button>
    <button type="submit" class="btn btn-info pull-right" id="submit">保　存</button>
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

    //上传图片
    var image_url = $('#image_url').val();
    var images = [];
    var title_url = $('.title_url').val();
    var title_images = [];

    if (image_url == null || image_url.length > 0) {
        images = ['<img height="240" src="' + $('#image_url').val() + '">'];
    }

    if (title_url == null || title_url.length > 0) {
        title_images = ['<img height="240" src="' + $('.title_url').val() + '">'];
    }

    $('#image_file').fileinput({
        language: 'zh',
        uploadExtraData: {_token: '{{ csrf_token() }}'},
        allowedFileExtensions: ['jpg', 'gif', 'png'],
        initialPreview: images,
        maxFileSize: 10240,
        resizeImage: true,
        maxImageWidth: 640,
        maxImageHeight: 960,
    });

    $('#image_file').on('fileuploaded', function (event, data) {
        $('#image_url').val(data.response.data);
    });

    $('.image_title').fileinput({
        language: 'zh',
        uploadExtraData: {_token: '{{ csrf_token() }}'},
        allowedFileExtensions: ['jpg', 'gif', 'png'],
        initialPreview: title_images,
        maxFileSize: 10240,
        resizeImage: true,
        maxImageWidth: 640,
        maxImageHeight: 960,
    });

    $('.image_title').on('fileuploaded', function (event, data) {
        $('#string1').val(data.response.data);
    });


    $('.submit').click(function () {
        var image_file = $('#image_file').fileinput('getFileStack');
        if (image_file.length > 0) {
            return toastrs('请先上传图片!');
        }
    });

    $('.submit').click(function () {
        var image_title = $('.image_title').fileinput('getFileStack');
        if (image_title.length > 0) {
            return toastrs('请先上传图片!');
        }
    });

    //提示上传图片
    function toastrs(message) {
        toastr.options = {
            'closeButton': true,
            'positionClass': 'toast-bottom-right',
        };
        toastr['warning'](message);
        return false;
    }

    $(document).ready(function () {
        CKEDITOR.replace('description', {
            extraPlugins: 'uploadimage,image2',
            height: 900,
            filebrowserUploadUrl: '{{ url('/admin/files/upload') }}?_token={{ csrf_token()}}',
            contentsCss: [CKEDITOR.basePath + 'contents.css', '/css/admin/app.css'],
            image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
            image2_disableResizer: true
        });
    });

    //增加题目
    $(".add_option_title").click(function () {
        var i = $(".sub_title").size();

        var item = $(".file").size();

        if (item >= 1) {
            var i = $(".sub_title").size();
            var n = i + 1;
            var html =
                '<div class="form-group sub_title" data-id=sub_title>' +
                '<label class="col-sm-1 control-label">题目(' + n + '):</label>' +
                '<div class="col-sm-11">' +
                '<div class="input-group">' +
                '<input type="text" id="sub_title" name="sub_title[]" class="form-control">' +
                '<span class="input-group-addon sub_title_del">' +
                '<span class="glyphicon glyphicon-remove"></span>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '</div>';

            $(".file:last").after(html);//在选项后边追加
        } else {
            var n = i + 1;
            var html =
                '<div class="form-group sub_title" data-id=sub_title>' +
                '<label class="col-sm-1 control-label">题目(' + n + '):</label>' +
                '<div class="col-sm-11">' +
                '<div class="input-group">' +
                '<input type="text" id="sub_title" name="sub_title[]" class="form-control">' +
                '<span class="input-group-addon sub_title_del">' +
                '<span class="glyphicon glyphicon-remove"></span>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '</div>';
            //在每个题目的最后一个选项后添加.
            $(".sub_title:last").after(html);

        }

        //删除file的个数  删除题目的时候,要先删除选项.
        $(".sub_title_del").click(function () {
            $(this).parent().parent().parent().remove();
        })
        return false;
    })

    //清空第一个file的内容//新增页面
    $(".sub_title_del2").click(function () {
        $("#item_sub_title").val('');
    })

    //点击删除 //编辑页面原本存在的通过这个删除
    $('.sub_title_del1').click(function () {
        $file = $(this).parent().children('.form-control').attr('id');

        if ($file == 'item_sub_title1') {
            $(this).parent().children('.form-control').val('');
        } else {
            $(this).parent().parent().parent().remove();
        }
    })


    //增加选项
    $(".add_option").click(function () {
        var title = $(".sub_title").size(); //题目数大于1的时候,选项数就重置

        var i = $(".file").size();

        if (title >= 2) {
            var i = $(".file").size() - 6; //重置选项数从 标题的个数-1 开始 todo
            var n = i + 1;
            var html =
                '<div class="form-group file" data-id=file>' +
                '<label class="col-sm-1 control-label">选项:</label>' +
                '<div class="col-sm-11">' +
                '<div class="input-group">' +
                '<input type="text" id="sub_title_item_' + title + '" name="sub_title_item_' + title + '[]" class="form-control">' +
                '<span class="input-group-addon file_del">' +
                '<span class="glyphicon glyphicon-remove"></span>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '</div>';

            $(".sub_title:last").after(html);
        } else {
            var i = $(".file").size();
            var n = i + 1;
            var html =
                '<div class="form-group file" data-id=file>' +
                '<label class="col-sm-1 control-label">选项:</label>' +
                '<div class="col-sm-11">' +
                '<div class="input-group">' +
                '<input type="text" id="sub_title_item_1" name="sub_title_item_1[]" class="form-control">' +
                '<span class="input-group-addon file_del">' +
                '<span class="glyphicon glyphicon-remove"></span>' +
                '</span>' +
                '</div>' +
                '</div>' +
                '</div>';
            $(".file:last").after(html); //往选项的下边追加
        }

        //删除file的个数
        $(".file_del").click(function () {
            $(this).parent().parent().parent().remove();
        })
        return false;
    })

    //清空第一个file的内容//新增页面
    $(".file_del2").click(function () {
        $("#item_title").val('');
    })

    //点击删除 //编辑页面原本存在的通过这个删除
    $('.file_del1').click(function () {
        $file = $(this).parent().children('.form-control').attr('id');

        if ($file == 'item_title1') {
            $(this).parent().children('.form-control').val('');
        } else {
            $(this).parent().parent().parent().remove();
        }
    })
</script>

