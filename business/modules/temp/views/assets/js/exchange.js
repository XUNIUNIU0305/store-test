$(function() {
	//获取兑换信息
	$('#J_search_btn').on('click', function() {
		var id = $('#J_exchange_id').val().trim();
		if (id == '') return;
		requestUrl('/temp/exchange/query', 'POST', {pick_id: id}, function(data) {
			$('.business-water-info').removeClass('hidden');
			$('.error').addClass('hidden');
			//判断是否可兑换
			if (data.status === true) {
				$('.exchange_info').removeClass('hidden');
				$('#J_pick_id').html(data.pick_id);
				$('#J_pay_time').html(data.pay_time);
				$('#J_custom_user').html(data.custom_user);
				$('#J_exchange_btn').off().on('click', function() {
					requestUrl('/temp/exchange/exchange', 'POST', {pick_id: data.pick_id}, function(data) {
						$('#apxModalBusinessAlert').modal('show');
						$('#apxModalBusinessAlert').on('hidden.bs.modal', function() {
							window.location.reload();
						})
					})
				})
			} else {
				$('.error').removeClass('hidden');
				var errorMsg = ['', '错误兑换码，请核对后再尝试兑换。', '该兑换码未支付。', '该兑换码已兑换。']
				if (data.error === 4) {
					$('.error').html('所属运营商错误！请前往<span class="high-lighted">' + data.message + '</span>兑换。');
				} else {
					$('.error').html(errorMsg[data.error]);
				}
			}
		})
	})
	//回车
	$('#J_exchange_id').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('#J_search_btn').click();
		}
	})
	//攻略遮罩层
	$('.water-strategy').on('click', function() {
		$(window).scrollTop(0);
		$('.water-strategy-content').removeClass('hidden');
	})
	$('.water-strategy-content .close').on('click', function() {
		$('.water-strategy-content').addClass('hidden');
	})
})