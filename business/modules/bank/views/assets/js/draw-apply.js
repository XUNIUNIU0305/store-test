$(function() {
	var g = {
		is_active: false,
        balance: 0,
        is_bind: false
	}


	// 提现时间判断
	var _timeH = new Date().getHours()
	var _timeS = new Date().getMinutes()
	if (_timeH >= 9 && _timeH <= 16) {
		if (_timeH === 16) {
			if (_timeS < 30) {
				$('#J_next_btn').removeClass('disabled')
			}
		} else {
			$('#J_next_btn').removeClass('disabled')
		}
	}

	// 公共弹窗
    function showMsg(msg) {
        $('#businessCommonAlertMsg').html(msg);
        $('#businessCommonAlert').modal('show');
    }
	// 获取账户余额
    function getBalance() {
        requestUrl('/user/balance', 'GET', '', function(data) {
            $('#J_user_balance').html(parseFloat(data.rmb).toFixed(2));
            g.balance = data.rmb;
        })
    }
    getBalance()

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
                $('#J_bank_logo').attr('src', data.card.bank_logo);
                $('#J_bank_name').html(data.card.bank_name);
                $('#J_user_name').html(data.card.acct_name);
                $('#J_bank_code').html(data.card.acct_no);
                g.is_bind = true;
                if (!data.card.is_active) {
                	$('#J_go_active').removeClass('hidden');
                } else {
				    g.is_active = true;
				}
			}
		})
	}
	getInfo()
	$('#J_extract_num').on('keyup', function(event) {
        if(!(event.keyCode>=48 && event.keyCode<=57)){ 
            $(this).val($(this).val().replace(/\D/g,'')); 
        } 
	})

	// 下一步
	$('#J_next_btn').on('click', function() {
        if (!g.is_bind) {
			showMsg('请先绑定银行账户！')
            return
        }
		if (!g.is_active) {
			showMsg('请先激活此卡！')
			return
		}
		var val = $('#J_extract_num').val() - 0;
		if (val > g.balance - 0) {
			showMsg('账户余额不足！')
			return
		}
		if (val.length < 1 || val % 100 !== 0 || val == 0) {
			showMsg('提现金额必须为100的整数倍！');
			return
		}
		$('#J_price').html(val + '元');
		$('#J_input_box').addClass('hidden');
		$('#J_sure_box').removeClass('hidden');
	})

	// 修改
	$('#J_back').on('click', function() {
		$('#J_input_box').removeClass('hidden');
		$('#J_sure_box').addClass('hidden');
	})

	// 获取验证码
	// get sms
    var timerAll = [],
        intervalAll = [];
	$('#J_captcha_btn').on('click', function(e) {
		var $that = $(this);
    	//启用计时启
		function startTimer() {
			var timer, interval;
			e.preventDefault();
			var $this =$that;
			var countDown = 60;
			// disable it
			//if ($this.hasClass('disabled')) return;
			$this.addClass('disabled');
			// revert changes after 60s
			timer = setTimeout(function () {
				$this.text('点击获取');
				$this.removeClass('disabled');
				interval && clearInterval(interval);
			}, 60 * 1000);
			timerAll.push(timer);
			// set count down text
			$this.text(countDown + '秒后重试');
			interval = setInterval(function () {
				countDown--;
				$this.text(countDown + '秒后重试');
			}, 1000);
			intervalAll.push(interval);
        }
        requestUrl('/sms/draw', 'POST', '' , function(data) {
        	startTimer()
        })
	})
	// 确定提现
	$('#J_extract_sure').on('click', function() {
		var captcha = $('#J_captcha_input').val().trim();
		var pwd = $('#J_pass_word').val().trim();
		var val = $('#J_extract_num').val().trim();
		requestUrl('/bank/draw-apply/create', 'POST', {rmb: val, captcha: captcha, password: pwd}, function(data) {
			showMsg('操作成功！');
			$('#businessCommonAlert').one('hidden.bs.modal', function() {
				window.location.reload()
			})
		})
	})

	// 获取用户信息
	!function() {
		requestUrl('/main/user-info', 'GET', '', function(data) {
			$('#J_tip_mobile').html('验证码将发送至 ' + data.mobile )
		})
	}()
})