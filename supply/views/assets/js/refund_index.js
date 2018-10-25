$(function() {
	//获取售后单列表
	var tpl = $('#J_tpl_refund').html();
	function status(data) {
		if (data == 0) {
			return '待客服审核'
		}
		if (data == 1) {
			return '待厂家审核'
		}
		if (data == 2) {
			return '已驳回'
		}
		if (data == 3) {
			return '已过审'
		}
		if (data == 4) {
			return '退回中'
		}
		if (data == 5) {
			return '已退款'
		}
		if (data == 6) {
			return '换货中'
		}
		if (data == 7) {
			return '已换货'
		}
		if (data == 8) {
			return '确认退款'
		}
		if (data == 9) {
			return '已取消'
		}
	}
	function price(data) {
		return (data - 0).toFixed(2)
	}
	function attr(data) {
		var attrs = '';
		for (var i = 0; i < data.length; i++) {
			attrs += data[i].attribute + '：' + data[i].option + ";"
		}
		return attrs
	}
	juicer.register('price_build', price);
	juicer.register('status_build', status);
	juicer.register('attr_build', attr);
	function getRefundList(page, size, status, code, type) {
		var data = {
			current_page: page,
			page_size: size,
			refund_status: status,
			refund_type: type,
			refund_code: code
		}
		requestUrl('/refund/get-refund-list', 'GET', data, function(data) {
			var html = juicer(tpl, data);
			var pages = getPagination(page, Math.ceil(data.total_count/size));
			if (status === 1) {
				$('#J_todo_refund').html(html);
				//分页
				$('#J_todo_page').html(pages);
				$('#J_todo_page #J_page_box li').on('click', function() {
					var val = $(this).data('page');
					if (val == undefined) {
						return false
					}
					getRefundList(val, size, status)
				})
				$('#J_todo_page #J_page_search input').on('keyup', function() {
					var number = $(this).val().replace(/\D/g,'') - 0;
					$(this).val(number);
					if ($(this).val().length < 1) {
						$(this).val('1');
						return false
					}
					if ($(this).val() < 1) {
						$(this).val('1');
						return false
					}
					if ($(this).val() > $('#J_todo_page #J_page_box').data('max')) {
						$(this).val($('#J_todo_page #J_page_box').data('max'))
						return false
					}
				})
				$('#J_todo_page #J_page_search a').on('click', function() {
					var n = $('#J_todo_page #J_page_search input').val();
					if (n > $('#J_todo_page #J_page_box').data('max')) {
						alert('已超过最大分页数')
						return false;
					}
					getRefundList(n, size, status)
				})
			} else if (status === -1) {
				$('#J_done_refund').html(html);
				//分页
				$('#J_done_page').html(pages);
				$('#J_done_page #J_page_box').attr('id', 'J_done_page_box');
				$('#J_done_page #J_done_page_box li').on('click', function() {
					var val = $(this).data('page');
					if (val == undefined) {
						return false
					}
					getRefundList(val, size, status)
				})
				$('#J_done_page #J_page_search input').on('keyup', function() {
					var number = $(this).val().replace(/\D/g,'') - 0;
					$(this).val(number);
					if ($(this).val().length < 1) {
						$(this).val('1');
						return false
					}
					if ($(this).val() < 1) {
						$(this).val('1');
						return false
					}
					if ($(this).val() > $('#J_done_page #J_done_page_box').data('max')) {
						$(this).val($('#J_done_page #J_done_page_box').data('max'))
						return false
					}
				})
				$('#J_done_page #J_page_search a').on('click', function() {
					var n = $('#J_done_page #J_page_search input').val();
					if (n > $('#J_done_page #J_done_page_box').data('max')) {
						alert('已超过最大分页数')
						return false;
					}
					getRefundList(n, size, status)
				})
			} else if (status === 4){
				$('#J_doing_refund').html(html);
				//分页
				pagingBuilder.build($('#J_doing_page'), page, size, data.total_count);
				pagingBuilder.clickPage($('#J_doing_page'), function(page) {
					getRefundList(page, size, status)
				})
				pagingBuilder.clickSearch($('#J_doing_page'), function(page) {
					getRefundList(page, size, status)
				})
			} else if (status == 9) {
				$('#J_cancel_refund').html(html);
				//分页
				pagingBuilder.build($('#J_cancel_page'), page, size, data.total_count);
				pagingBuilder.clickPage($('#J_cancel_page'), function(page) {
					getRefundList(page, size, status)
				})
				pagingBuilder.clickSearch($('#J_cancel_page'), function(page) {
					getRefundList(page, size, status)
				})
			}
		})
	}
	getRefundList(1, 20, 1)
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var status = $('#J_tab_box li[class*="active"]').data('status');
		getRefundList(1, 20, status)
	});
	//售后单搜索
	$('#J_search_btn').on('click', function() {
		var val = $(this).siblings('input').val();
		var status = $('#J_tab_box li[class*="active"]').data('status');
		getRefundList(1, 20, status, val)
	}).siblings('input').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$(this).siblings('#J_search_btn').click()
		}
	})

})
