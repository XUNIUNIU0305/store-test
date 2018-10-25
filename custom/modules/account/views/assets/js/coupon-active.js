$(function() {
	var code = '', pwd = '';
	$('#J_info_btn').on('click', function() {
		$('#J_coupon_info .error-msg').removeClass('show');
		code = '';
		$('.J_coupon_code').each(function(v) {
			code += $(this).val()
		});
		pwd = $('#J_coupon_pwd').val();
		if (code.length !== 15) {
			$('#J_coupon_info .error-msg').eq(0).addClass('show');
			return;
		}
		if (pwd.length !== 8) {
			$('#J_coupon_info .error-msg').eq(1).addClass('show');
			return;
		}
		requestUrl('/account/coupon/get-ticket-info', 'GET', {code: code, password: pwd}, function(data) {
			$('#J_coupon_info').addClass('hidden');
			//未被激活
			if (data.status === 0) {
				$('#J_coupon_inactive').removeClass('hidden');
			}
			//已被激活
			if (data.status === 1) {
				$('#J_coupon_used').removeClass('hidden');
			}
			$('.J_coupon_name').html(data.Coupon.name);
			$('.J_receive_limit').html(data.Coupon.receive_limit);
			$('.J_coupon_price').html(data.Coupon.price);
			$('.J_consume_limit').html(data.Coupon.consume_limit);
			$('.J_start_time').html(data.Coupon.start_time);
			$('.J_end_time').html(data.Coupon.end_time);
		})
	})
	//确认激活按钮
	$('#J_active_sure').on('click', function() {
		var yes = confirm('确认激活？');
		if (!yes) {
			return;
		}
		requestUrl('/account/coupon/active-ticket', 'POST', {code: code, password: pwd}, function(data) {
			alert('激活成功！');
			window.location.href = '/account/coupon/index';
		})
	})
	//取消按钮
	$('.J_cancel_btn').on('click', function() {
		$('#J_coupon_info').removeClass('hidden');
		$('#J_coupon_inactive').addClass('hidden');
		$('#J_coupon_used').addClass('hidden');
		$('#J_coupon_code').val('');
		$('#J_coupon_pwd').val('');
	})
})