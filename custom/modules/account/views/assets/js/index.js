// 判断是否出现优惠券弹窗
$(function () {
	if (CUSTOM_USER_LOGIN) {
		requestUrl('/account/coupon/get-ticket-list', 'GET', {status: 1}, function (data) {
			if (data.total_count > 0) {
				$('#modalFreeLunch .close-btn').css({ 'background-image': "url('/images/account/x.png')", 'background-size': '100%' });
				$('#modalFreeLunch').modal('show');
			}
		})
	}
})
$(function() {
	$('.account-header').removeClass('hidden');
	//获取最近订单列表
	var tpl_lately_order = $('#J_tpl_lately_order').html();
	function getLatelyOrder() {
		requestUrl('/account/order/list', 'GET', {current_page: 1, page_size: 5}, function(data) {
			$('#J_lately_list').html(juicer(tpl_lately_order, data));
		})
	}
	getLatelyOrder()
})