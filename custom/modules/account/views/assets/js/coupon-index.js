$(function() {
	//获取优惠券列表
	var tpl = $('#J_tpl_coupon').html();
	var compiled_tpl = juicer(tpl);
	function supplier(data) {
		if (data === false) {
			return '全场可用'
		}
		return data.brand_name + '专用'
	}
	juicer.register('supplier_build', supplier);
	function getCouponList(page, size, status) {
		var data = {
			current_page: page,
			page_size: size,
			status: status
		}
		requestUrl('/account/coupon/get-ticket-list', 'GET', data, function(data) {
			var html = compiled_tpl.render(data);
			if (data.codes == '') {
				return
			}
			if (status === 1) {
				//已激活
				$('#J_coupon_activated').html(html);
				pagingBuilder.build($('#J_activated_page'), page, size, data.total_count);
				pagingBuilder.click($('#J_activated_page'), function(page) {
					getCouponList(page, size, status);
				});
			}
			if (status === 2) {
				//已使用
				$('#J_coupon_used').html(html);
				pagingBuilder.build($('#J_used_page'), page, size, data.total_count);
				pagingBuilder.click($('#J_used_page'), function(page) {
					getCouponList(page, size, status);
				});
			}
		})
	}
	getCouponList(1, 20, 1);
	$('a[data-toggle="tab"]').one('shown.bs.tab', function(e) {
		if ($(e.target).attr('href') == '#coupon_used') {
			getCouponList(1, 20, 2)
		} 
	})
})