$(function() {
	// var _code = '';
	// //获取注册码
	// function getCode() {
	// 	var _data = {
	// 		a: url('?a')
	// 	}
	// 	$.ajax({
	// 		url: '/partner/code',
	// 		data: _data,
	// 		success: function(data) {
	// 			if (data.status == 200) {
	// 				if (data.data.code === '') {
	// 					setTimeout(function() {
	// 						getCode()
	// 					}, 1000)
	// 				} else {
	// 					_code = data.data.code;
	// 					$('.code').text(data.data.code);
	// 					$('.loading').addClass('hidden');
	// 					$('.content').removeClass('hidden');
	// 					$('.btn').removeClass('disabled');
	// 				}
	// 			} else {
	// 				alert(data.data.errMsg)
	// 			}
	// 		},
	// 		error: function(data) {
	// 			alert(data)
	// 		}
	// 	})
	// }
	// getCode()
	// //跳转注册页
	// $('#jump').on('click', function() {
	// 	if ($('.content').hasClass('hidden')) return;
	// 	window.location.href = $('#host').text() + '/member/register?r=' + _code;
	// })
	var num = 4;
	setInterval(function() {
		if (num > 0) {
			num--;
			$('#J_num').text(num);
		} else {
			$('#jump').click();
		}
	}, 1000)
})