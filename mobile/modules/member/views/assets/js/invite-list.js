$(function() {
	//获取邀请纪录
	function getInviteLog(page, size, id, search) {
		var _data = {
			current_page: page,
			page_size: size,
			id: id,
			search_text: search || ''
		}
		$.ajax({
			url: '/member/invite/invite-log',
			data: _data,
			success: function(data) {
				if (data.status == 200) {
					var data = data.data;
					var html = '', status = '';
					for (var i = 0; i < data.data.length; i++) {
						if (data.data[i].status == 1) {
							status = '待提交'
						}
						if (data.data[i].status == 2) {
							status = '待审核'
						}
						if (data.data[i].status == 3) {
							status = '审核失败'
						}
						if (data.data[i].status == 4) {
							status = '待开通'
						}
						if (data.data[i].status == 5) {
							status = '已开通'
						}
						html += '<div class="item">\
									<span>' + data.data[i].account + '</span>\
									<span>' + status + '</span>\
									<span>' + data.data[i].mobile + '</span>\
								</div>'
					}
					$('#J_code_list').html(html)
				} else {
					alert(data.data.errMsg)
				}
			},
			error: function(data) {
				alert(data)
			}
		})
	}
	getInviteLog(1, 9999, url('?id'))
	//获取总邀请数量
	function getInviteNum() {
		$.ajax({
			url: '/member/invite/invite-num',
			data: '',
			success: function(data) {
				if (data.status == 200) {
					$('#J_invite_num').text(data.data.count)
				} else {
					alert(data.data.errMsg)
				}
			},
			error: function(data) {
				alert(data)
			}
		})
	}
	getInviteNum()
	//搜索
	$('#J_search_btn').on('click', function() {
		$(this).parents('.info').addClass('hidden').siblings('.search').removeClass('hidden')
	})
	$('#J_close_btn').on('click', function() {
		$(this).parents('.search').addClass('hidden').siblings('.info').removeClass('hidden')
	})
	$('#J_search_sure').on('click', function() {
		var val = $('#J_search_input').val().trim();
		getInviteLog(1, 9999, url('?id'), val)
	})
})