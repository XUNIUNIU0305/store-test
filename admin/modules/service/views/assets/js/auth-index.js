$(function() {
	//获取审核列表
	var g = {
		page: 1,
		size: 100,
		tpl: $('#J_tpl_list').html(),
		page_box: $('#J_auth_page'),
		current_page: 1
	}
	function status(data) {
		if (data == 1 || data == '' || data == 3) {
			return '未提交'
		}
		if (data == 2) {
			return '未审核'
		}
		if (data == 4 || data == 5) {
			return '已审核'
		}
	}
	juicer.register('status', status); // 注册自定义函数
	
	function getAuthList(page, size, status, search, time) {
		var _data = {
			current_page: page,
			page_size: size,
			status: status || '',
			search_text: search || '',
			time: time || 'desc',
			cancel: 0
		}
		requestUrl('/service/auth/get-list', 'GET', _data, function(data) {
			$('#J_auth_list').html(juicer(g.tpl, data));
			g.current_page = page;
			// 判断当前状态
			if (status != 4 && status != 5) {
				$(".auth-check-box").removeClass('hidden');
			} else {
				$(".auth-check-box").addClass('hidden');
			}
			pagingBuilder.build(g.page_box, page, size, data.total_count);
			pagingBuilder.click(g.page_box, function(page) {
				getAuthList(page, size, status, search, time)
			})
		})
	}
	getAuthList(g.page, g.size, "2", "", "desc")

	// 跳转详情页
	$('#J_auth_list').on('click', 'td', function(e) {
		if(!$(this).hasClass('no-jump')) {
			location.href = $(this).parent('tr').data('link');
		}
	})

	//通过审核
	function passInfo(id) {
		requestUrl('/service/auth/pass', 'POST', { aid: id }, function (data) {
			$('#apxModalAdminCommonAlert').modal('show');
			$('.J_check_all').prop('checked', false);
			$('#J_auth_page .active').click();
		})
	}
	$('#apxModalAdminAuth').on('show.bs.modal', function(e) {
		$('#apxModalAdminAuth').data('id', $(e.relatedTarget).data("id"));
	})
	// 单个通过
	$('#J_auth_sure').on('click', function () {
		$('#apxModalAdminAuth').modal('hide');
		var id = $('#apxModalAdminAuth').data('id');
		passInfo([id])
	})
	// 批量通过
	$('#J_auth_sure_multi').on('click', function() {
		$('#apxModalAdminAuthAll').modal('hide');
		var id = [];
		$.each($('input[name="single"]'), function(i, val) {
			if ($(val).prop('checked')) {
				id.push($(val).data('id'))
			}
		})
		if (id.length < 1) {
			return
		}
		passInfo(id)
	})

	// 全选
	$('.J_check_all').on('click', function() {
		var checked = $(this).prop('checked');
		$('.J_check_all').prop('checked', checked);
		$('input[name="single"]').prop('checked', checked);
	})
	$('#J_auth_list').on('click', 'input[name="single"]', function() {
		var checked = true;
		$.each($('input[name="single"]'), function(i, val) {
			if (!$(val).prop('checked')) {
				checked = false;
				return
			}
		})
		$('.J_check_all').prop('checked', checked);
	})

	//搜索
	//账号
	$('#J_search_text').on('click', function() {
		var val = $('#J_search_input').val();
		var status = $('#J_search_status button[class*="active"]').data('id');
		var time = $('#J_search_time button[class*="active"]').data('id');
		getAuthList(g.page, g.size, status, val, time)
	})

	//状态查询
	$('#J_search_status').on('click', 'button', function() {
		$(this).addClass('active').siblings('button').removeClass('active');
		var val = $('#J_search_input').val();
		var status = $('#J_search_status button[class*="active"]').data('id');
		var time = $('#J_search_time button[class*="active"]').data('id');
		getAuthList(g.page, g.size, status, val, time);
		if (status == '1,3' || status == '') {
			$('#J_search_time').addClass('hidden')
		} else {
			$('#J_search_time').removeClass('hidden')
		}
	})

	//按时间查询
	$('#J_search_time').on('click', 'button', function() {
		$(this).addClass('active').siblings('button').removeClass('active');
		var val = $('#J_search_input').val();
		var status = $('#J_search_status button[class*="active"]').data('id');
		var time = $('#J_search_time button[class*="active"]').data('id');
		getAuthList(g.page, g.size, status, val, time)
	})
})
