$(function() {
	//提示信息弹窗
	$('#J_bind_btn').off('click').on('click',function () {
		if ( $(this).attr('data-type') === 'bound') {
			//解绑
			$('#J_add_role').off('click').on('click',function () {
				if ( $('#J_password').val() === '') {
					$('.error-msg').removeClass('hidden')
				} else {
					requestUrl('/wechat/unbind-account','POST',{passwd:$(J_password).val()}, function () {
						$('#J_password').val('')
						$('#apxModalAdminAlertEnterRole').modal('hide')
						$('.error-msg').addClass('hidden')
						getMsg()
					})
				}
			})
		} else {
			//绑定
			$('#J_add_role').off('click').on('click',function () {
				if ( $('#J_password').val() === '') {
					$('.error-msg').removeClass('hidden')
				} else {
					requestUrl('/wechat/bind-url','POST',{passwd:$(J_password).val()}, function (data) {
						$('#J_password').val('')
						$('#apxModalAdminAlertEnterRole').modal('hide')
						$('.error-msg').addClass('hidden')
						window.location = data.url
						getMsg()
					})
				}
			})
		}
	
	})

	function getMsg () {
		//获取微信绑定信息
		requestUrl('/wechat/user-info', 'GET', '', function(data) {
			if (Object.keys(data).length < 1) {
					$('#J_bind_btn').attr('data-type','unbound').text('立即绑定')
					$('#J_binding_status').text('未绑定').addClass('unbound')
					$('#J_prompt_msg').text('绑定后可以使用微信一键登录九大爷购物平台！')
					$('.detail-right').addClass('hidden')
			} else {
				$('.detail-right').removeClass('hidden')
				$('#J_binding_status').text('已绑定').removeClass('unbound')
				$('#J_bind_btn').attr('data-type','bound').text('解除绑定')
				$('#J_prompt_msg').text('解绑后需要使用账号进行登录，请牢记您的账号！')
				$('#J_bind_name').html(data.username)
				$('#J_bind_img').attr('src', data.head_img)
			}
		})
	}

	getMsg()

	$('#aside_panel_banding_1').on('show.bs.collapse', function () {
		$('#J_bind_show').text('收起')
	})

	$('#aside_panel_banding_1').on('hide.bs.collapse', function () {
		$('#J_bind_show').text('展开')
	})

})