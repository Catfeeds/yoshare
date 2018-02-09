<div class="cb-toolbar">操作:</div>
<div class="btn-group margin-bottom">
    <input type="hidden" name="state" id="state" value=""/>
    <button class="btn btn-primary btn-xs margin-r-5" id="btn_create" onclick="window.location.href='{{ $base_url }}' + '/create';">新增</button>
    <button class="btn btn-success btn-xs margin-r-5 state" value="{{ \App\Models\Order::STATE_PUBLISHED }}">发布</button>
    <button class="btn btn-danger btn-xs margin-r-5" id="btn_delete" value="{{ \App\Models\Order::STATE_DELETED }}" onclick="remove()" data-toggle="modal" data-target="#modal">删除</button>
    <button class="btn btn-default btn-xs margin-r-5" id="btn_sort">排序</button>
</div>
<div class="btn-group margin-bottom pull-right">
    <button type="button" class="btn btn-info btn-xs margin-r-5 filter" data-active="btn-info" value="">全部</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5 filter" data-active="btn-primary" value="{{ \App\Models\Order::STATE_NOPAY }}">未付款</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5 filter" data-active="btn-success" value="{{ \App\Models\Order::STATE_PAID }}">已付款，未发货</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5 filter" data-active="btn-warning" value="{{ \App\Models\Order::STATE_SENDED }}">已发货</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5 filter" data-active="btn-danger" value="{{ \App\Models\Order::STATE_CLOSED }}">交易关闭</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5 filter" data-active="btn-success" value="{{ \App\Models\Order::STATE_SUCCESS }}">交易成功</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5 filter" data-active="btn-danger" value="{{ \App\Models\Order::STATE_REFUND }}">退款中</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5" data-toggle="modal" data-target="#modal_query"><span class="fa fa-search"></span></button>
</div>
