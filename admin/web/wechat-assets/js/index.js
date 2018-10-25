$(function() {
	//获取推广员信息
	function getPromoter(q) {
		$.ajax({
			url: '/partner/promoter',
			data: {q: q},
			success: function(data) {
				if (data.status === 200) {
					if (data.data.type === 1) {
                        $('#promoterID').html(data.data.title + ' / ' + data.data.remark)
						// 微信分享
						wechatShare(data.data.title + ' / ' + data.data.remark)
					} else if (data.data.type === 2) {
						$('#promoterID').html(data.data.title)
						// 微信分享
						wechatShare(data.data.title)
                    }
				} else {
					alert(data.data.errMsg)
				}
			},
			error: function(data) {
				alert(data)
			}
		})
	}
	getPromoter(url('?q'))

	// 打开关闭遮罩层
	$('#J_open_info').on('click', function(e) {
		e.preventDefault()
		$('.write-custom-info').addClass('in');
		$('.new-invite-custom').addClass('no-scroll');
	})

	$('.close').on('click', function() {
		$('.write-custom-info').removeClass('in');
		$('.new-invite-custom').removeClass('no-scroll');
	})

	//获取短信验证码
	function getCaptcha(mobile) {
		var data = {
			mobile: mobile,
			q: url('?q')
		}
		$.ajax({
			url: '/partner/captcha',
			type: 'POST',
			data: data,
			success: function(data) {
				if (data.status === 200) {
					
				} else {
					alert(data.data.errMsg)
				}
			},
			error: function(data) {
				alert(data)
			}
		})
	}
	$('#captcha-btn').on('click', function() {
		if ($(this).hasClass('disabled')) return;
		var mobile = $('#mobile').val().trim();
		var resultMB = mobile.search(/0?(1)[0-9]{10}/);
		
        if (resultMB == -1) {
            alert('手机号格式错误！')
            return
        }
		startTimer()
		getCaptcha(mobile)
	})

	var timerAll = [],
        intervalAll = [];
	//启用计时启
    function startTimer() {
        var timer, interval;
        var $this = $('#captcha-btn');
        var countDown = 60;
        // disable it
        if ($this.hasClass('disabled')) return;
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
    //支付
    var _PAY_FLAG = true;
    $('#pay_now').on('click', function() {
        if (!_PAY_FLAG) {return};
    	var data = {}
    	data.q = url('?q')
    	data.mobile = $('#mobile').val().trim();
    	data.captcha = $('#captcha').val().trim();
		var resultMB = data.mobile.search(/0?(1)[0-9]{10}/);
        if (resultMB == -1) {
            alert('手机号格式错误！')
            return
		}
		var passwd = $('#J_pass_wd').val();
		var confirm_passwd = $('#J_confirm_passwd').val();
		if (passwd.length < 8) {
			alert('密码长度至少为8位！')
			return
		}
		if (passwd !== confirm_passwd) {
			alert("两次密码不一致！")
			return
		}
        if (data.captcha.length < 1) {
        	alert('验证码不能为空')
        	return
		}
		data.passwd = passwd;
		data.confirm_passwd = confirm_passwd;
        _PAY_FLAG = false;
    	$.ajax({
    		url: '/partner/apply',
    		type: 'POST',
    		data: data,
    		success: function(data) {
    			if (data.status == 200) {
    			    window.location.href = data.data.url
    			} else {
    				alert(data.data.errMsg)
    			}
    		},
    		error: function(data) {
    			alert(data)
    		},
            complete: function(data) {
                _PAY_FLAG = true;
            }
    	})
    })
    //虚拟键盘弹出处理
    $('input').on('focus', function() {
        var $this = $(this)
    	setTimeout(function() {
            $this[0].scrollIntoView()
        }, 1000)
	})
	
	// 微信分享
	function wechatShare(name) {
		var img = window.location.origin + '/images/weixin_img.jpg';
		var title = '您的好友' + name + '邀您进驻九大爷平台';
		var desc = '扫码、注册、付一元；送钱、送礼、送特权！';
		var urlS = window.location.href;
		var url = urlS.split('&code')[0];
		$.ajax({
			url: 'http://106.14.255.215/api/9daye',
			data: {
				m: 'm_js_sdk',
				url: urlS
			},
			success: function (data) {
				var data = data.data;
				wx.config({
					debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
					appId: data.appId, // 必填，公众号的唯一标识
					timestamp: data.timestamp, // 必填，生成签名的时间戳
					nonceStr: data.nonceStr, // 必填，生成签名的随机串
					signature: data.signature,// 必填，签名，见附录1
					jsApiList: ['checkJsApi', 'getLocation', 'chooseImage', 'uploadImage', 'onMenuShareWeibo', 'onMenuShareQZone', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ']
				});
				wx.ready(function () {
					//分享到朋友圈
					wx.onMenuShareTimeline({
						title: title, // 分享标题
						link: url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
						imgUrl: img, // 分享图标
						success: function () {
							// 用户确认分享后执行的回调函数
						},
						cancel: function () {
							// 用户取消分享后执行的回调函数
						}
					});
					//分享给朋友
					wx.onMenuShareAppMessage({
						title: title, // 分享标题
						desc: desc, // 分享描述
						link: url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
						imgUrl: img, // 分享图标
						type: '', // 分享类型,music、video或link，不填默认为link
						dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
						success: function () {
							// 用户确认分享后执行的回调函数
						},
						cancel: function () {
							// 用户取消分享后执行的回调函数
						}
					});
					//分享到QQ
					wx.onMenuShareQQ({
						title: title, // 分享标题
						desc: desc, // 分享描述
						link: url, // 分享链接
						imgUrl: img, // 分享图标
						success: function () {
							// 用户确认分享后执行的回调函数
						},
						cancel: function () {
							// 用户取消分享后执行的回调函数
						}
					});
					//分享到QQ微博
					wx.onMenuShareWeibo({
						title: title, // 分享标题
						desc: desc, // 分享描述
						link: url, // 分享链接
						imgUrl: img, // 分享图标
						success: function () {
							// 用户确认分享后执行的回调函数
						},
						cancel: function () {
							// 用户取消分享后执行的回调函数
						}
					});
					//分享到QQ空间
					wx.onMenuShareQZone({
						title: title, // 分享标题
						desc: desc, // 分享描述
						link: url, // 分享链接
						imgUrl: img, // 分享图标
						success: function () {
							// 用户确认分享后执行的回调函数
						},
						cancel: function () {
							// 用户取消分享后执行的回调函数
						}
					});
				})
			},
			error: function (data) {
				console.log(data)
			}
		})
	};
})