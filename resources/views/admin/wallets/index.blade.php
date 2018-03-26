<div class="row">
    <div class="col-xs-12">
        <div class="box box-info">
            @include('admin.comments.reply')
            <div class="box-body">
                <table id="comment_table" data-toggle="table" style="word-break:break-all;">
                    <thead>
                    <tr>
                        <th data-field="id" data-width="60">ID</th>
                        <th data-field="username" data-width="100">会员</th>
                        <th data-field="points" data-width="125">积分</th>
                        <th data-field="deposit" data-width="120">押金</th>
                        <th data-field="balance" data-width="120">余额</th>
                        <th data-field="coupon" data-width="120">优惠券</th>
                        <th data-field="state_name" data-width="60" data-align="center" data-formatter="walletStateFormatter">状态</th>
                        <th data-field="state_name" data-width="60" data-align="center" data-formatter="refundFormatter">押金操作</th>
                        <th data-field="created_at" data-width="130">创建时间</th>
                        <th data-field="action" data-width="100" data-align="center" data-formatter="commentActionFormatter"
                            data-events="commentActionEvents"> 操作
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $('#comment_table').bootstrapTable({
        method: 'get',
        url: '/admin/wallets/table',
        pagination: true,
        pageNumber: 1,
        pageSize: 8,
        pageList: [10, 25, 50, 100],
        sidePagination: 'server',
        clickToSelect: true,
        striped: true,
        queryParams: function (params) {
            params.member_id = '{{ $member_id }}';
            params._token = '{{ csrf_token() }}';
            return params;
        },
    });

    function walletStateFormatter(value, row, index) {
        var style = 'label-primary';
        switch (row.state_name) {
            case '申请退还':
                style = 'label-primary';
                break;
            case '已退款':
                style = 'label-warning';
                break;
            case '已删除':
                style = 'label-danger';
                break;
        }
        return [
            '<span class="label ' + style + '">' + row.state_name + '</span>',
        ].join('');
    }


    function refundFormatter(value, row, index) {
        if(row.state == {{ \App\Models\Wallet::STATE_REFUNDING }}){
            var disabled_del = '';
            switch (row.state_name) {
                case '已退还':
                    disabled_del = 'disabled="disabled"';
                    break;
            }
            return [
                '<a class="refund" href="javascript:void(0)"><button class="btn btn-danger btn-xs" ' + disabled_del + '  onclick="confirm('+row.id+')">退款</button></a>'
            ].join('');
        }
    }

    function commentActionFormatter(value, row, index) {
        var disabled_del = '';
        switch (row.state_name) {
            case '已删除':
                disabled_del = 'disabled="disabled"';
                break;
        }
        return [
            '<a class="edit" href="javascript:void(0)"><button class="btn btn-primary btn-xs" ' + disabled_del + ' >修改</button></a>'
        ].join('');
    }

    window.commentActionEvents = {
        'click .edit': function (e, value, row, index) {
            window.location.href = '/admin/wallets/' + row.id + '/edit';
        },
    };

    function confirm(id) {
        toastr.options = {
            'closeButton': true,
            'showDuration': 100,
            'hideDuration': 0,
            'timeOut': 0,
            'extendedTimeOut': 0,
            'positionClass': 'toast-top-center',
        };
        toastr['info']('您确定退还该用户押金吗？&nbsp;&nbsp;&nbsp;<span onclick="refund('+id+');" style="text-decoration: underline;">确定</span>');
    }

    function refund(id) {
        var url = '/admin/wallets/refund/'+id;
        $.ajax({
            url: url,
            type: "post",
            data: {
                '_token': '{{ csrf_token() }}',
            },
            success: function (data) {
                msg = data.message;
                statusCode = data.status_code;
                if(statusCode == 200){
                    toast('success', '退款成功！');
                }else{
                    toast('error', '退款失败！');
                }
            }
        });
    }

</script>