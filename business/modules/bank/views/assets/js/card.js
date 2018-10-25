$(function() {

	// 全局数据
	var g = {
		key: ''
	}

	// 公共弹窗
    function showMsg(msg) {
        $('#businessCommonAlertMsg').html(msg);
        $('#businessCommonAlert').modal('show');
    }
	// 获取银行卡信息
	function getInfo() {
		requestUrl('/bank/card/binded-card', 'GET', '', function(data) {
			// 未绑卡
			if (data.is_bind === false) {
				$('#J_no_bind').removeClass('hidden');
			}
			// 已绑卡
			if (data.is_bind) {
                $('#J_is_bind').removeClass('hidden');
                $('#J_bank_img').attr('src', data.card.bank_logo);
                $('#J_bank_name').html(data.card.bank_name);
                if (data.card && data.card.acct_type == 0) {
                    $('#J_bank_type').html('企业账号');
                } else {
                	$('#J_bank_type').html('个人账号');
                }
                $('#J_user_name').html(data.card.acct_name);
                $('#J_bank_code').html(data.card.acct_no);
                if (data.card.is_active) {
                	$('#J_bank_status').html('');
                } else {
                	$('#J_bank_status').html('未激活');
                	$('#J_active_btn').removeClass('hidden');
                }
                $('#J_handle_box').removeClass('hidden');
                g.key = data.card.ver_seq_no;
			}
		})
	}
	getInfo()
	// 解绑卡
	$('#J_unbind_btn').on('click', function() {
		$('#modalBandkTip').modal('hide');
		$('#modalLoading').modal({
            backdrop: 'static',
            keyboard: false
        })
		requestUrl('/bank/card/unbind', 'POST', '', function(data) {
			if (data.is_success) {
				setTimeout(function() {
					$('#modalLoading').modal('hide');
					showMsg('解绑成功！');
					$('#businessCommonAlert').one('hidden.bs.modal', function() {
						window.location.reload()
					})
				}, 3000)
			} else {
				$('#modalLoading').modal('hide');
				showMsg(data.err_msg);
			}
		}, function(data) {
			$('#modalLoading').modal('hide');
			alert(data.data.errMsg)
		})
	})

	// 激活卡片
	$('#J_active_btn').on('click', function() {
		if (g.key.length < 1) {
			$('#modalBandkConfirm').modal('show')
		} else {
			$('#J_handle_box').addClass('hidden').siblings('#J_input_box').removeClass('hidden');
		}
	})
	$('#J_active_sure').on('click', function() {
		$('#modalBandkConfirm').modal('hide');
		$('#modalLoading').modal({
            backdrop: 'static',
            keyboard: false
        })
		requestUrl('/bank/card/trans-amount', 'POST', '', function(data) {
			if (data.is_success) {
				setTimeout(function() {
					$('#modalLoading').modal('hide');
					showMsg('申请激活成功！')
					g.key = data.ver_seq_no;
					$('#J_handle_box').addClass('hidden').siblings('#J_input_box').removeClass('hidden');
				}, 3000)
			} else {
				$('#modalLoading').modal('hide');
				showMsg(data.err_msg);
			}
		}, function(data) {
			$('#modalLoading').modal('hide');
			showMsg(data.data.errMsg)
		})
	})
	$('#J_submit_money').on('click', function() {
		var money = $('#J_money_input').val().trim();
		$('#modalLoading').modal({
            backdrop: 'static',
            keyboard: false
        })
		requestUrl('/bank/card/activate', 'POST', {check_amount: money, ver_seq_no: g.key}, function(data) {
			if (data.is_success) {
				setTimeout(function() {
					$('#modalLoading').modal('hide');
					showMsg('激活成功！')
					$('#businessCommonAlert').one('hidden.bs.modal', function() {
						window.location.reload()
					})
				}, 3000)
			} else {
				$('#modalLoading').modal('hide');
				showMsg(data.err_msg);
			}
		}, function(data) {
			alert(data.data.errMsg)
		})
	})
	$('#J_cancle_btn').on('click', function() {
		$('#J_handle_box').removeClass('hidden').siblings('#J_input_box').addClass('hidden');
	})
})