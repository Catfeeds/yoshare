@if(Session::has('flash_success'))
    <div class="alert alert-success">
        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="icon fa fa-check"></i>{{ session('flash_success') }}
    </div>
@elseif(Session::has('flash_warning'))
    <div class="alert alert-warning">
        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="icon fa fa-warning"></i>{{ session('flash_warning') }}
    </div>
@elseif(Session::has('flash_info'))
    <div class="alert alert-info">
        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="icon fa fa-info"></i>{{ session('flash_info') }}
    </div>
@elseif(Session::has('flash_error'))
    <div class="alert alert-danger">
        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="icon fa fa-ban"></i>{{ session('flash_error') }}
    </div>
@endif

@if (count($errors) > 0)
    <div class="am-g">
        <div class="am-u-md-12">
            <div class="am-alert am-alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

