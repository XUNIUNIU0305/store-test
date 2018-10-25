$(function() {
	var tpl = $('#J_tpl_list').html();
	function status(data) {
		if (data == 2) {
			return '已付款'
		}
		if (data == 3) {
			return '已接单'
		}
		if (data == 4) {
			return '已完成'
		}
		if (data == 5) {
			return '已取消'
		}
	}
	juicer.register('status', status);
	// 获取订单列表
	function getOrderList(page, size, status) {
		var data = {
			page: page,
			page_size: size,
			status: status || ''
		}
		requestUrl('/membrane/order/list', 'GET', data, function(data) {
			$('#J_order_list').html(juicer(tpl, data));
		})
	}
	getOrderList(1, 999)
	// 切换
	$('#J_nav_list span').on('click', function() {
		$(this).addClass('active').siblings('span').removeClass('active');
		var status = $(this).data('status');
		getOrderList(1, 999, status)
	})
	// 详情
	$('#J_order_list').on('click', '.list-main', function() {
		var no = $(this).data('no');
		window.location.href = '/membrane/order/detail?no=' + no;
	})
	// 取消订单
	$('#J_list_box').on('click', '.J_cancel_btn', function() {
		var yes = confirm('确定要取消该订单吗？');
		if (!yes) return;
		var no = $(this).data('no');
		requestUrl('/membrane/order/cancel', 'POST', {no: no}, function(data) {
			var status = $('#J_nav_list .active').data('status');
			getOrderList(1, 999, status)
		})
	})
})