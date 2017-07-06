<div class="form-group">
    {!! Form::label('name', '会员名:',['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        @if(isset($member))
            <h5>{!! $member->name !!}</h5>
            {!! Form::hidden('name', null, ['class' => 'form-control']) !!}
        @else
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label($password, '密码:',['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::password($password, null, ['class' => 'form-control' ,'placeholder'=>$placeholder]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('nick_name', '昵称:', ['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::text('nick_name', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">会员类型</label>
    <div class="col-sm-10">
        <label><input class="default" type="radio" name="type" value="{{ \App\Models\Member::TYPE_NORMAL }}"
                    {{ !isset($member) || isset($member) && $member->type == \App\Models\Member::TYPE_NORMAL ? 'checked': '' }}>普通会员&nbsp;&nbsp;
        </label>
        <label><input type="radio" name="type" value="{{ \App\Models\Member::TYPE_TEACHER }}"
                    {{ isset($member) && $member->type == \App\Models\Member::TYPE_TEACHER ? 'checked': '' }} >讲师</label>
    </div>
</div>

<div class="form-group" style="display: none;">
    {!! Form::label('avatar_url', '头像地址:', ['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::text('avatar_url', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label for="image_file" class="control-label col-sm-2">上传头像:</label>
    <div class=" col-sm-10">
        <input id="image_file" name="image_file" type="file" data-preview-file-type="text"
               data-upload-url="/admin/files/upload?type=image">
    </div>
</div>

<div class="box-footer">
    <button type="button" class="btn btn-default" onclick="window.history.back();">取　消</button>
    <button type="submit" class="btn btn-info pull-right" id="submit">确　定</button>
</div>

<script>
    var image_url = $('#avatar_url').val();
    var images = [];

    if (image_url == null || image_url.length > 0) {
        images = ['<img height="240" src="' + $('#avatar_url').val() + '">'];
    }

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
        $('#avatar_url').val(data.response.data);
    });

    $('#image_file').on('filedeleted', function (event, key) {
        $('#avatar_url').val('');
    });

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
            return toastrs('请先上传头像');
        }
    })
</script>