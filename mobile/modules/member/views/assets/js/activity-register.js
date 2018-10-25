$(function() {
	// 替换title
	var _address = {
		'wh': '武汉线下活动专用注册页',
		'gz': '广州线下活动专用注册页'
	}
	document.title = _address[url('?c')]

    //提交
    $('#J_register_submit').on('click.submit', function() {
    	var code = $('#registerCode').val();
    	var passwd1 = $('#pwd').val();
    	var passwd2 = $('#pwd_confirm').val();
    	var province = $('#selProvince').val();
    	var city = $('#selCity').val();
    	var district = $('#selDistrict').val();
    	var mobile = $('#mobile').val();
    	var email = $('#email').val();
    	var validate = $("#valid").val();
    	
    	if (passwd1 == '' || passwd2 == '' || mobile == '' ) {
    		alert('请把信息填写完整！')
    		return
    	}
    	var resultPW = passwd1.search(/[^0-9a-zA-Z]/g);
    	if (resultPW != -1) {
    		alert('密码格式错误！');
    		return
    	}
    	if (passwd1 != passwd2) {
    		alert('两次密码不一致！');
    		return
    	}
    	if (passwd1.length < 8) {
    		alert('密码长度至少为8位！');
    		return
    	}
    	
    	var resultMB = mobile.search(/0?(1)[0-9]{10}/);
    	if (resultMB == -1) {
    		alert('手机号格式错误！');
    		return
		}
		if (validate == '') {
			alert('验证码不能为空！')
			return
		}
    	$('#J_register_submit').addClass('disabled');
    	var _data = {
			passwd: passwd1,
			confirm_passwd: passwd2,
            mobile: mobile,
            c: url('?c'),
            verify_code: validate
    	}
    	function submitCB(data) {
    		alert('成功！')
    		window.location.href = data.url
    	}
    	function errCB(data) {
    		$('.J_register_submit').removeClass('disabled');
    		alert(data.data.errMsg)
    	}
    	requestUrl('/member/activity-register/member-register', 'POST', _data, submitCB, errCB)
    })

    // get sms
    var timerAll = [],
        intervalAll = [];
    $('.J_get_verify_sms').click(function (e) {

        var mobile = $('#mobile').val();
        var resultMB = mobile.search(/0?(1)[0-9]{10}/);
        if (resultMB == -1) {
            alert('手机号格式错误！');
            return
        }

        var _data={
			mobile: mobile,
			c: url('?c')
		};

        var $that=$(this);

    	//启用计时启
		function startTimer(){
			var timer, interval;
			e.preventDefault();
			var $this =$that;
			var countDown = 60;
			// disable it
			// if ($this.hasClass('disabled')) return;
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

        function submitCB(data){
			startTimer();
		}
        //错误返回
        function errCB(data) {
            $('.J_get_verify_sms').removeClass('disabled');
            alert(data.data.errMsg)
        }
        $that.addClass('disabled');

        requestUrl('/member/activity-register/send-captcha', 'GET', _data, submitCB, errCB)

	})
	// 限制手机号输入
	$('#mobile').on('keyup', function() {
		var val = $(this).val();
		$(this).val(val.replace(/[^0-9]/g, ''))
	})
})