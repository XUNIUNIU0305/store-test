$(function() {
	var scrolls = [];
	$('.iscroll_container').each(function () {
	    scrolls.push(new IScroll(this, {
	        mouseWheel: true,
	        scrollbars: true,
	        scrollbars: 'custom'
	    }))
	})

	//获取部门列表
	var tpl_department = $('#J_tpl_department').html();
	function getDepartmentList(page, size) {
		function departmentListCB(data) {
			var html = juicer(tpl_department, data.codes);
			$('#J_department_list').html(html);
			scrolls.forEach(function (scroll) {
				scroll.refresh();
			})
			//分页
			var pages = getPagination(page, Math.ceil(data.total_count/size));
			$('.department_page').html(pages);
			$('#J_page_box li').on('click', function() {
				var val = $(this).data('page');
				if (val == undefined) {
					return false
				}
				getDepartmentList(val, size)
			})
			$('#J_page_search input').on('keyup', function() {
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
				if ($(this).val() > $('#J_page_box').data('max')) {
					$(this).val($('#J_page_box').data('max'))
					return false
				}
			})
			$('#J_page_search a').on('click', function() {
				var n = $('#J_page_search input').val();
				if (n > $('#J_page_box').data('max')) {
					alert('已超过最大分页数')
					return false;
				}
				getDepartmentList(n, size)
			})
		}
		requestUrl('/site/department/getdepartmentlist', 'GET', {current_page: page, page_size: size}, departmentListCB);
	}
	getDepartmentList(1, 20)
	//部门关联用户
	$('#J_department_list').on('click', '.J_department', function() {
		var $this = $(this);
		$this.addClass('active').siblings().removeClass('active');
		var id = $this.data('id');
		function userCB(data) {
			var len = data.length, li = '';
			for (var i = 0; i < len; i++) {
				li += '<li>' + data[i].name + '</li>'
			}
			$('#J_department_users').html(li);
			scrolls.forEach(function (scroll) {
				scroll.refresh();
			})
		}
		requestUrl('/site/department/getemployeelist', 'GET', {id: id}, userCB)
	})
	//添加部门
	$('#apxModalAdminAlertEnterDepartment').on('show.bs.modal', function(e) {
		var $this = $(e.relatedTarget);
		$('#apxModalAdminAlertEnterDepartment #department').val('');
	})
	$('.J_add_department').on('click', function() {
		var name = $('#department').val();
		if (name == '') return;
		$('#apxModalAdminAlertEnterDepartment').modal('hide');
		function addCB(data) {
			$('#J_alert_content').html('添加成功！');
			$('#apxModalAdminAlert').modal('show');
			getDepartmentList(1, 20);
		}
		requestUrl('/site/department/add', 'POST', {name: name}, addCB)
	})
	//删除部门
	$('#apxModalAdminDepartmentDel').on('show.bs.modal', function(e) {
		var $this = $(e.relatedTarget).parents('.J_department');
		var id = $this.data('id');
		var name = $this.find('.J_department_name').html();
		$('#apxModalAdminDepartmentDel .J_dele_name').html(name);
		$('#apxModalAdminDepartmentDel').data('id', id);
		$('#apxModalAdminDepartmentDel .J_dele_sure').off().on('click', function() {
			$('#apxModalAdminDepartmentDel').modal('hide');
			if ($('#J_department_users li').length > 0) {
				$('#apxModalAdminAlertDisable').modal('show');
				return
			}
			function delCB(data) {
				$('#J_alert_content').html('删除成功！');
				$('#apxModalAdminAlert').modal('show');
				getDepartmentList(1, 20);
			}
			requestUrl('/site/department/delete', 'POST', {id: id}, delCB)
		})
	})

	










})