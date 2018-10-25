$(function() {
	buildNewAlertInHere();
	//获取管理员列表
	var tpl_list = $('#J_tpl_admin').html();
	var compile_list = juicer(tpl_list);
	function getAdmin(page, size, is, account) {
		var data = {
			current_page: page,
			page_size: size,
			account: account || '',
			is_admin: is
		}
		requestUrl('/site/admin/list', 'GET', data, function(data) {
			if (is === 1) {
				data.is = 1;
				data.text = '解 除';
				data.target = 'apxModalBusinessReset'
			}
			if (is === 0) {
				data.is = 0;
				data.text = '设 置';
				data.target = 'apxModalBusinessAdd'
			}
			var html = compile_list.render(data);
			if (is) {
				$('#J_admin_list').html(html);
			} else {
				$('#J_unadmin_list').html(html);
			}
			pagingBuilder.build($('#J_admin_page'), page, size, data.total_count);
			pagingBuilder.click($('#J_admin_page'), function(page) {
				getAdmin(page, size, is, account);
			});
		})
	}
	getAdmin(1, 12, 1);
	//切换
	$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
		var $this = $(e.target);
		var is = $this.data('is');
		getAdmin(1, 12, is);
	})
	//拉取省级列表
	$('#apxModalBusinessAdd').on('show.bs.modal', function(e) {
		var $this = $(e.relatedTarget);
		if ($('#J_area_list option').length < 1) {
			requestUrl('/leader/area/list', 'GET', {parent_id: 0}, function(data) {
				var html = '<option value="-1">' + '请选择' + '</option>';
				$.each(data.list, function(k, v) {
					html += '<option value="' + data.list[k].id + '">' + data.list[k].name + '</option>'
				})
	            $('#J_area_list').html(html);
	            $('.selectpicker').selectpicker('refresh');
	            $('.selectpicker').selectpicker('show');
			})
		}
		var id = $this.data('id');
		$(this).data('id', id);
		$('#unadmin_id').html($this.parents('li').find('.admin_id').html());
	})
	//设置管理员
	$('#J_sure_add').on('click', function() {
		var id = $('#apxModalBusinessAdd').data('id');
		var area = $('#J_area_list').val();
		if (area === '-1') {
			showAlert('请选择省份！');
			return;
		}
		$('#apxModalBusinessAdd').modal('hide');
		requestUrl('/site/admin/set', 'POST', {account: id, area_id: area}, function(data) {
			showAlert('设置成功！');
			getAdmin(1, 12, 0);
		})
	})
	//解除管理员
	$('#apxModalBusinessReset').on('show.bs.modal', function(e) {
		var $this = $(e.relatedTarget);
		$(this).data('id', $this.data('id'));
		$('#admin_id').html($this.parents('li').find('.admin_id').html());
	})
	$('#J_sure_reset').on('click', function() {
		$('#apxModalBusinessReset').modal('hide');
		var id = $('#apxModalBusinessReset').data('id');
		requestUrl('/site/admin/remove', 'POST', {account: id}, function(data) {
			showAlert('解除成功！');
			getAdmin(1, 12, 1);
		})
	})
	//搜索
	$('#J_search_btn_admin').on('click', function() {
		var account = $('#J_search_input_admin').val();
		if (account !== '') {
			getAdmin(1, 12, 1, account)
		}
	})
	$('#J_search_btn_unadmin').on('click', function() {
		var account = $('#J_search_input_unadmin').val();
		if (account !== '') {
			getAdmin(1, 12, 0, account)
		}
	})
})