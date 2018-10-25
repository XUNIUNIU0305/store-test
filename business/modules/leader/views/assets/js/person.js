$(function() {
	var scrolls = [];
	$('.iscroll_container').each(function () {
	    scrolls.push(new IScroll(this, {
	        mouseWheel: true,
	        scrollbars: true,
	        scrollbars: 'custom',
	        preventDefault: false
	    }))
	})
	function refreshScroll() {
		setTimeout(function() {
			scrolls.forEach(function (scroll) {
				scroll.refresh();
			})
		}, 300)
	}
	//创建提示弹窗
	buildNewAlertInHere()

	//获取用户列表
	var tpl_account = $('#J_tpl_list').html();
	var	account = juicer(tpl_account);
	function getUserList(page, size, user) {
		var data = {
			current_page: page || 1,
			page_size: size || 20,
			user: user || ''
		}
		requestUrl('/leader/person/list', 'GET', data, function(data) {
			var html = account.render(data);
			$('#J_account_list').html(html);
			pagingBuilder.build($('#J_account_page'), page, size, data.total_count);
			pagingBuilder.click($('#J_account_page'), function(page) {
				getUserList(page, size, user)
			})
			refreshScroll();
		})
	}
	getUserList(1, 20)
	//搜索
	$('#J_search_btn').on('click', function() {
		var val = $('#J_search_input').val();
		getUserList(1, 20, val)
	})
	$('#J_search_input').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('#J_search_btn').click()
		}
	})
	//添加新员工
	$('#J_add_new_person').on('click', function() {
		var name = $('#J_new_name').val().trim();
		var remark = $('#J_new_remark').val().trim();
		if (name == '') {
			showAlert('姓名不能为空！');
			return;
		}
		if (name.length < 2) {
			showAlert('姓名长度应大于2！');
			return;
		}
		var data = {
			name: name,
			remark: remark
		}
		$('#apxModalManageAddPerson').modal('hide');
		requestUrl('/leader/person/add-user', 'POST', data, function(data) {
			showAlert('添加成功！');
			getUserList(1, 20);
			$('#J_new_name').val('');
			$('#J_new_remark').val('');
		})
	})
	//删除用户
	$('#apxModalBusinessDel').on('show.bs.modal', function(e) {
		var $this = $(e.relatedTarget);
		$('#apxModalBusinessDel .user_name').html($this.parents('.J_account_box').data('name'));
		var id = [$this.parents('.J_account_box').data('id')];
		if ($this.data('btn') == 'batch') {
			id = [];
			for (var i = 0; i < $('[name="account"]:checked').length; i++) {
				id.push($('[name="account"]:checked').eq(i).parents('.J_account_box').data('id'))
			}
		}
		$('#J_sure_del').off().on('click', function() {
			$('#apxModalBusinessDel').modal('hide');
			requestUrl('/leader/person/remove', 'POST', {users_id: id}, function(data) {
				showAlert('删除成功！');
				getUserList(1, 20);
			})
		})
	})
	//查看用户详情
	$('#J_account_list').on('show.bs.collapse', '.collapse-flag', function(e) {
		var $this = $(e.target);
		var id = $this.parents('.J_account_box').data('id');
		refreshScroll();
		if ($this.data('status') === true) {
			//获取备注
			requestUrl('/leader/person/remark', 'GET', {user_id: id}, function(data) {
				$this.parents('.J_account_box').find('.account_remark').html(data.remark);
				$this.data('status', false);
			})
			//获取职位和区域
			requestUrl('/leader/person/position', 'GET', {user_id: id}, function(data) {
				$this.parents('.J_account_box').find('.account_role').html(data.role);
				var area = '';
				$.each(data.area, function(k, v) {
					area += v + '&nbsp;&nbsp;'
				})
				$this.parents('.J_account_box').find('.account_area').html(area);
				$this.data('status', false);
			})
			//获取用户业绩
			requestUrl('/leader/person/achievement', 'GET', {user_id: id}, function(data) {
				$this.parents('.J_account_box').find('.yesterday_achievement').html(data.yesterday.toFixed(2));
				$this.parents('.J_account_box').find('.life_achievement').html(data.life.toFixed(2));
				$this.data('status', false);
			})
		}
	})
	//解除用户角色
	$('#apxModalBusinessReset').on('show.bs.modal', function(e) {
		var $this = $(e.relatedTarget);
		$('#apxModalBusinessReset .user_name').html($this.parents('.J_account_box').data('name'));
		var id = $this.parents('.J_account_box').data('id');
		$('#J_sure_reset').off().on('click', function() {
			$('#apxModalBusinessReset').modal('hide');
			requestUrl('/leader/person/reset', 'POST', {users_id: [id]}, function(data) {
				showAlert('解除成功！');
				getUserList(1, 20);
			})
		})
	})
	//修改员工信息
	$('#apxModalManageEdit').on('show.bs.modal', function(e) {
		var $this = $(e.relatedTarget).parents('.J_account_box');
		var id = $this.data('id');
		$(this).data('id', id);
		var name = $this.data('name');
		$('#J_edit_name').val(name);
		//获取备注
		requestUrl('/leader/person/remark', 'GET', {user_id: id}, function(data) {
			$('#J_edit_remark').val(data.remark);
		})
	})
	$('#J_edit_sure').on('click', function() {
		var id = $('#apxModalManageEdit').data('id');
		var name = $('#J_edit_name').val();
		var remark = $('#J_edit_remark').val();
		if (name == '') {
			showAlert('请填写完整信息！');
			return;
		}
		var data = {
			user_id: id,
			name: name,
			remark: remark
		}
		$('#apxModalManageEdit').modal('hide');
		requestUrl('/leader/person/modify', 'POST', data, function(data) {
			showAlert('修改成功！');
			$('#J_account_page li[class*="active"]').click();
		})
	})
})