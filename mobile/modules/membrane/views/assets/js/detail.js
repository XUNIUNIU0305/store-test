$(function() {
	var tpl = $('#J_tpl_pro').html();
	// 获取订单详情
	function getOrderDetail(no) {
		requestUrl('/membrane/order/view', 'GET', {no: no}, function(data) {
			$('.J_name').html(data.receiveName);
			$('#J_detail').html(data.receiveAddress);
			$('.J_mobile').html(data.receiveMobile);
			$('#J_no').html(data.no);
			$('#J_pay_time').html(data.createdDate);
			$('#J_account').html(data.account);
			if (data.status == 2) {
				$('#J_status').html('已付款')
			}
			if (data.status == 3) {
				$('#J_status').html('已接单')
			}
			if (data.status == 4) {
				$('#J_status').html('已完成')
			}
			if (data.status == 5) {
				$('#J_status').html('已取消')
			}
			var count = data.items.length;
			$('#J_count').html(count);
			$('.J_price').html('￥' + data.totalFee.toFixed(2));
			$('#J_pro_box').html(juicer(tpl, data));
			if (data.payMethod == 1) {
				$('#J_payment').html('余额支付')
			}
			if (data.payMethod == 3) {
				$('#J_payment').html('微信支付')
			}
		})
	}
	getOrderDetail(url('?no'))
})