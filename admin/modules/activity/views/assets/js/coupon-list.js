$(function () {
    //获取优惠券列表
    var tpl = $('#J_tpl_list').html();
    function supplier(data) {
    	if (data === false) {
    		return '无使用限制'
    	}
    	return data.brand_name
    }
    juicer.register('supplier_build', supplier);
    function getCouponList(page, size, status, name) {
    	var data = {
    		current_page: page,
    		page_size: size,
    		status: status,
    		name: name || ''
    	}
    	requestUrl('/activity/coupon/get-coupon-list', 'GET', data, function(data) {
            var html = juicer(tpl, data);
            if (status === 0) {
                $('#J_coupon_inuse').html(html);
                pagingBuilder.build($('#J_coupon_page'), page, size, data.total_count);
                pagingBuilder.click($('#J_coupon_page'), function(page) {
                    getCouponList(page, size, status)
                })
            }
            if (status === 2) {
                $('#J_coupon_used').html(html);
                pagingBuilder.build($('#J_used_page'), page, size, data.total_count);
                pagingBuilder.click($('#J_used_page'), function (page) {
                    getCouponList(page, size, status)
                })
            }
    	})
    }
    getCouponList(1, 20, 0)
    //搜索优惠券
    $('#J_search_btn').on('click', function() {
        var status = $('.nav-tabs li[class*="active"] a').data('status');
        getCouponList(1, 20, status, $('#J_search_input').val().trim());
    })
    //删除优惠券
    $('#apxModalAdminDel').on('show.bs.modal', function(e) {
        var $this = $(e.relatedTarget);
        var id = $this.data('id');
        $('#J_del_name').html($this.data('name'));
        $('#J_sure_del').off().on('click', function() {
            $('#apxModalAdminDelSure').modal('hide');
            requestUrl('/activity/coupon/delete-coupon', 'GET', {id: id}, function(data) {
                $('#J_alert_content').html('删除成功!');
                $('#apxModalAdminAlert').modal('show');
                getCouponList(1, 20, 0)
            }, function(data) {
                $('#J_alert_content').html(data.data.errMsg);
                $('#apxModalAdminAlert').modal('show');
            })
        })
    })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var status = $(this).data('status');
        getCouponList(1, 20, status)
    })
})
