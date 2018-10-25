$(function() {
	//弹窗
	buildNewAlertInHere();
	
	//获取手机验证码
	$('#J_get_verify').on('click', function() {
		$('#verify').parents('.form-group').find('.error-msg').addClass('hidden');
		$('#mobile').parents('.form-group').find('.error-msg').addClass('hidden');
		var mobile = $('#user_mobile').val();
		var id = $('#register_id').val();
		if (mobile.search(/0?(1)[0-9]{10}/) == -1) {
			$('#user_mobile').parents('.form-group').find('.error-msg span').html('填写错误！');
			$('#user_mobile').parents('.form-group').find('.error-msg').removeClass('hidden');
			return
		}
		$this = $(this);
		requestUrl('/sms/reset', 'POST', {account: id, mobile: mobile}, function(data) {
			startTimer($this);
		}, function(data) {
			$('#verify').parents('.form-group').find('.error-msg span').html(data.data.errMsg);
			$('#verify').parents('.form-group').find('.error-msg').removeClass('hidden');
		})
	})
	//找回密码
	$('#J_register_btn').on('click', function() {
		$('.error-msg').addClass('hidden');
		var account = $('#register_id').val();
		var mobile =$('#user_mobile').val();
		var verify =$('#verify').val();
		var password =$('#password').val();
		var confrim =$('#confrim').val();
		for (var i = 0; i < $('input[type="text"]').length; i++) {
			if($('input[type="text"]').eq(i).val() == '') {
				$('input[type="text"]').eq(i).parents('.form-group').find('.error-msg').removeClass('hidden');
			}
		}
		//手机号
		var resultMB = mobile.search(/0?(1)[0-9]{10}/);
    	if (resultMB == -1) {
    		$('#mobile').parents('.form-group').find('.error-msg').removeClass('hidden');
    		return
    	}
    	//密码验证
    	if (password.search(/[^0-9a-zA-Z]/g) != -1) {
    		$('#password').parents('.form-group').find('.error-msg span').html('密码只能包含数字和字母！');
    		$('#password').parents('.form-group').find('.error-msg').removeClass('hidden');
    		return
    	}
    	if (password.length < 8) {
    		$('#password').parents('.form-group').find('.error-msg span').html('密码长度至少为8位！');
    		$('#password').parents('.form-group').find('.error-msg').removeClass('hidden');
    		return
    	}
    	if (confrim == '') {
    		$('#confrim').parents('.form-group').find('.error-msg span').html('不能为空！');
    		$('#confrim').parents('.form-group').find('.error-msg').removeClass('hidden');
    		return
    	}
    	if (password !== confrim) {
    		$('#password').parents('.form-group').find('.error-msg span').html('两次密码不一致！');
    		$('#password').parents('.form-group').find('.error-msg').removeClass('hidden');
    		return
    	}
    	var data = {
    		account: account,
    		mobile: mobile,
    		mobile_captcha: verify,
    		passwd: password,
    		passwd_confirm: confrim
    	}
    	requestUrl('/password/reset', 'POST', data, function(data) {
    		showAlert('密码重置成功！');
    		setTimeout(function() {
    			window.location.href = '/'
    		}, 5000)
    	})
	})
	//启用计时启
	var timerAll = [],
        intervalAll = [];
	function startTimer(dom){
		var timer, interval;
		var $this = dom;
		var countDown = 60;
		// disable it
		//if ($this.hasClass('disabled')) return;
		$this.addClass('disabled');
		// revert changes after 60s
		timer = setTimeout(function () {
			$this.text('获取验证码');
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
})