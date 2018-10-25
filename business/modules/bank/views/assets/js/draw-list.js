$(function() {
	// 获取提现列表
	var tpl = $('#J_tpl_list').html();
	function getList(params) {
		var _data = {
			current_page: 1,
			page_size: 20,
			status: 0
		}
		$.extend(_data, params);
		requestUrl('/bank/draw-list/list', 'GET', _data, function(data) {
			$('#J_steam_list').html(juicer(tpl, data.list));
			pagingBuilder.build($('#J_page_list'), _data.current_page, _data.page_size, data.total_count);
			pagingBuilder.click($('#J_page_list'), function(page) {
				getList({current_page: page})
			})
		})
	}
	getList()
	// 切换状态
	$('#J_tabs_list').on('click', '.btn-tab', function() {
		$(this).addClass('active').siblings('.btn-tab').removeClass('active');
		var status = $(this).data('status');
		getList({status: status})
	})
})