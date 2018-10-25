$(function() {
	var tpl = $('#J_tpl_list').html();
	// 获取领水券列表
	function getCouponList(status) {
		requestUrl('/member/water/list', 'GET', {current_page: 1, page_size: 999, used: status}, function(data) {
			$('#J_coupon_list').html(juicer(tpl, data));
		})
	}
	getCouponList(0)
	// 切换列表
	$('.head-nav p').on('click', function() {
		$(this).addClass('active').siblings('p').removeClass('active');
		var status = $(this).data('status');
		getCouponList(status);
	})
})