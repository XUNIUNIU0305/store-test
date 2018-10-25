$(function() {

	var g = {
		page_box: $('#J_cancel_page'),
		page: 1,
		size: 5,
		code: '',
		click_page: 1,
		search_text: '',
		time: 'desc',
		status: 0
	}
	// 获取注销列表
	var tpl = $('#J_tpl_list').html();
	function getCancelList(page, size) {
		var data = {
			id: url('?id'),
			cancel: 1,
			current_page: page,
			page_size: size,
			time: g.time,
			search_text: g.search_text,
			is_operation: g.status
		}
		g.click_page = page;
		requestUrl('/service/auth/get-list', 'GET', data, function(data) {
			$('#J_cancel_list').html(juicer(tpl, data));
			pagingBuilder.build(g.page_box, page, size, data.total_count);
			pagingBuilder.click(g.page_box, function(page) {
				getCancelList(page, size)
			})
		})
	}
	getCancelList(g.page, g.size)

	// 复制商户单号
	$('#J_cancel_list').on('click', '.J_copy_btn', function() {
		var $copy = $(this).parents('.item').find('.J_copy_no')[0];
		/* 创建range对象 */
		var range = document.createRange();
		range.selectNode($copy); // 设定range包含的节点对象 
		/* 窗口的selection对象，表示用户选择的文本 */
		var selection = window.getSelection();
		if(selection.rangeCount > 0) selection.removeAllRanges(); // 将已经包含的已选择的对象清除掉
		selection.addRange(range); // 将要复制的区域的range对象添加到selection对象中
		document.execCommand('copy'); // 执行copy命令，copy用户选择的文本
	})

	// 提交退款号
	$('#modalRefund').on('show.bs.modal', function(e) {
		var code = $(e.relatedTarget).data('code');
		g.code = code;
	})
	$('#J_sure_btn').on('click', function() {
		var code = g.code;
		var number = $('#J_refund_number').val().trim();
		if (number == '') {
			alert('退款号不能为空！')
			return
		}
		$('#modalRefund').modal('hide');
		requestUrl('/service/auth/refund', 'POST', {id: code, refund_number: number}, function(data) {
			getCancelList(g.click_page, g.size)
		})
	})

	// 按时间排序
	$('#J_sort_time button').on('click', function() {
		$(this).addClass('active').siblings('button').removeClass('active');
		g.time = $(this).data('id');
		getCancelList(g.page, g.size);
	})
	// 按状态排序
	$('#J_sort_status button').on('click', function() {
		$(this).addClass('active').siblings('button').removeClass('active');
		g.status = $(this).data('id');
		getCancelList(g.page, g.size);
	})
	// 搜索
	$('#J_search_btn').on('click', function() {
		var val = $('#J_search_input').val().trim();
		g.search_text = val;
		getCancelList(g.page, g.size);
	})
})