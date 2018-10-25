$(function() {
    var list_tpl = $('#J_tpl_list').html();
    function status(data) {
        if (data == 1) {
            return '未审核'
        }
        if (data == 2) {
            return '已审核'
        }
        if (data == 3) {
            return '执行成功'
        }
        if (data == 4) {
            return '执行失败'
        }
        if (data == 5) {
            return '已取消'
        }
    }
    function userType(data) {
        if (data == 1) {
            return 'CUSTOM'
        }
        if (data == 2) {
            return 'BUSINESS'
        }
    }
    function operateType(data) {
        if (data == 1) {
            return '入账'
        }
        if (data == 2) {
            return '出账'
        }
    }
    juicer.register('status', status);
    juicer.register('userType', userType);
    juicer.register('operateType', operateType);

    function getList(page, size, status) {
        var _data = {
            current_page: page,
            page_size: size,
            status: status
        }
        requestUrl('/fund/deposit-and-draw-list/list', 'GET', _data, function(data) {
            $('#order_status_' + status + ' .J_list_box').html(juicer(list_tpl, data.list))
            pagingBuilder.build($('#order_status_' + status + ' .J_page_box'), page, size, data.total_count);
            pagingBuilder.click($('#order_status_' + status + ' .J_page_box'), function(page) {
                getList(page, size, status)
            })
            if (url('?id')) {
                $('#order_status_' + status + ' .J_list_box tr[data-id="' + url("?id") + '"]').addClass('active');
            }
        })
    }
    if (url('?id')) {
        $('a[data-status="' + url('?status') + '"]').click();
        getList(url('?page'), 20, url('?status'));
    } else {
        getList(1, 20, 1);
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var status = $(e.target).data('status');
        getList(1, 20, status);
    })
    $('.table').on('click', '.J_list_item', function() {
        var id = $(this).data('id');
        if (!id) {
            return
        }
        var page = $(this).parents('table').siblings('.J_page_box').find('li[class*="active"]').data('page');
        var status =  $('.nav-tabs li[class*="active"]').find('a').data('status');
        location.href = '/fund/deposit-and-draw-detail?id=' + id + '&page=' + page + '&status=' + status;
    })
})