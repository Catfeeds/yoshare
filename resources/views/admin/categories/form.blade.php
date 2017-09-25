<ul id="tabs" class="nav nav-tabs">
    <li class="active">
        <a href="#tabHome" data-toggle="tab">基本信息</a>
    </li>
    <li>
        <a href="#tabContent" data-toggle="tab">正文</a>
    </li>
</ul>
<div id="tabContents" class="tab-content">
    <div id="tabHome" class="tab-pane fade in active padding-t-15">
        <div class="form-group">
            {!! Form::label('name', '栏目名称:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-11">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('title', '标题:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-11">
                {!! Form::text('title', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('subtitle', '副标题:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-11">
                {!! Form::text('subtitle', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('module_id', '模块:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::select('module_id', $modules, null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('sort', '序号:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('sort', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('state', '状态:',['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::select('state', \App\Models\Category::STATES, null, ['class' => 'form-control col-sm-2']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('link', '外链:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::select('link_type', \App\Models\Category::LINK_TYPES, null, ['class' => 'form-control','onchange'=>'return showLink(this.value,true)']) !!}
            </div>
            <div class="col-sm-8" id="link"></div>
        </div>

        <div class="form-group">
            {!! Form::label('description', '摘要:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-11">
                {!! Form::textarea('description', null, ['rows'=>'4','class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('cover_url', '封面地址:', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-11">
                {!! Form::text('cover_url', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="cover_file" class="control-label col-sm-1">上传封面:</label>
            <div class="col-sm-11">
                <input id="cover_file" name="cover_file" type="file" data-preview-file-type="text"
                       data-upload-url="/admin/files/upload?type=image">
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
            <div class="col-sm-11">
                <input id="image_file" name="image_file" type="file" data-preview-file-type="text"
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
</div>

<div id="tabGallery" class="tab-pane fade">
</div>
</div>

<div class="box-footer">
    <button type="button" class="btn btn-default" onclick="window.history.back();">取　消</button>
    <button type="submit" class="btn btn-info pull-right" id="submit">保　存</button>
</div><!-- /.box-footer -->

<script>
    var cover_url = $('#cover_url').val();
    var cover_image = [];

    var image_url = $('#image_url').val();
    var images = [];

    if (cover_url == null || cover_url.length > 0) {
        cover_image = ['<img height="240" src="' + $('#cover_url').val() + '">'];
    }

    if (image_url == null || image_url.length > 0) {
        images = ['<img height="240" src="' + $('#image_url').val() + '">'];
    }

    $('#cover_file').fileinput({
        language: 'zh',
        uploadExtraData: {_token: '{{ csrf_token() }}'},
        allowedFileExtensions: ['jpg', 'gif', 'png'],
        initialPreview: cover_image,
        initialPreviewAsData: false,
        initialPreviewConfig: [{key: 1}],
        deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
        maxFileCount: 1,
        fileActionSettings: {
            showZoom: false
        },
    });

    $('#cover_file').on('fileuploaded', function (event, data) {
        $('#cover_url').val(data.response.data);
    });

    $('#cover_file').on('filedeleted', function (event, key) {
        $('#cover_url').val('');
    });


    $('#image_file').fileinput({
        language: 'zh',
        uploadExtraData: {_token: '{{ csrf_token() }}'},
        allowedFileExtensions: ['jpg', 'gif', 'png'],
        initialPreview: images,
        initialPreviewAsData: false,
        initialPreviewConfig: [{key: 1}],
        deleteUrl: '/admin/files/delete?_token={{csrf_token()}}',
        maxFileSize: 10240,
        maxFileCount: 1,
        resizeImage: true,
        maxImageWidth: 640,
        maxImageHeight: 960,
        resizePreference: 'width',
        fileActionSettings: {
            showZoom: false
        },
    });

    $('#image_file').on('fileuploaded', function (event, data) {
        $('#image_url').val(data.response.data);
    });

    $('#image_file').on('filedeleted', function (event, key) {
        $('#image_url').val('');
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

    $('#submit').click(function () {
        var image_file = $('#image_file').fileinput('getFileStack');

        if (image_file.length > 0) {
            return toastrs('请先上传图片!');
        }

        @if(isset($parent_id) && $parent_id > 0)
          @if(isset($state) && $state == \App\Models\Category::STATE_DISABLED)
            @if(isset($parent_state) && $parent_state == \App\Models\Category::STATE_DISABLED)
                $('#window_msg').slideDown(100);
        $('#window_msg p').html('此栏目有父栏目，请先启用父栏目');
        return false;
        @endif
      @endif
    @endif

    $('#window_msg').hide();
    });

    $(document).ready(function () {
        CKEDITOR.replace('content', {
            filebrowserUploadUrl: '{{ url('files/upload') }}?_token={{csrf_token()}}',
        });
    });

    function showLink(type, is_edit) {
        if (type == '{{\App\Models\Category::LINK_TYPE_NONE}}') {
            $('#link').html('');
        } else if (type == '{{\App\Models\Category::LINK_TYPE_WEB}}') {
            $('#link').html('{!! Form::text('link', null, ['class' => 'form-control','id'=>'text']) !!}');
            if (is_edit == true) {
                $('#text').val('');
            }
        }
    }

    @if(isset($category))
      showLink('{{ $category->link_type }}', false);
    @endif
</script>