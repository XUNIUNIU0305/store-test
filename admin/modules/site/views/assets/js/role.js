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
function getDepartmentList() {
	function departmentCB(data) {
		var options = '';
		for (var i = 0, len = data.codes.length; i < len; i++) {
			options += '<option value="' + data.codes[i].id + '">' + data.codes[i].name + '</option>'
		}
		$('#J_department_select').append(options);
		$('#department').append(options);
	}
	requestUrl('/site/department/getdepartmentlist', 'GET', '', departmentCB);
}
getDepartmentList();
//获取角色列表
var tpl_role = $('#J_tpl_role').html();
function getRoleList(page, size) {
	function roleListCB(data) {
		var options = '<option value="-1">全部</option>';
		var checkbox = '';
		for (var i = 0, len = data.codes.length; i < len; i++) {
			options += '<option value="' + data.codes[i].id + '">' + data.codes[i].role_name + '</option>'
			checkbox +='<div class="checkbox">\
                            <label>\
                                <input type="checkbox" value="' + data.codes[i].id + '">\
                                ' + data.codes[i].role_name + '\
                            </label>\
                        </div>'
		}
		$('#J_role_select').html(options);
		$('#J_alert_role_list').html(checkbox);
		var html = juicer(tpl_role, data.codes);
		$('#J_role_list').html(html);
		scrolls.forEach(function (scroll) {
		    scroll.refresh();
		})
		//生成分页
		var pageHtml = getPagination(page, Math.ceil(data.total_count/size));
		$('.powerPage').html(pageHtml);
		$('.powerPage #J_page_box').attr('id', 'powerBox');
		$('.powerPage #J_page_search').attr('id', 'powerSearch');
		$('.powerPage li').on('click', function() {
			var val = $(this).data('page');
			if (val == undefined) {
				return false
			}
			getRoleList(val, size)
		})
		$('.powerPage #powerSearch input').on('keyup', function() {
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
			if ($(this).val() > $('.powerPage #powerBox').data('max')) {
				$(this).val($('.powerPage #powerBox').data('max'))
				return false
			}
		})
		$('.powerPage #powerSearch a').on('click', function() {
			var n = $('.powerPage #powerSearch input').val();
			if (n > $('.powerPage #powerBox').data('max')) {
				alert('已超过最大分页数')
				return false;
			}
			$('#powerBox li[data-page="' + n + '"]').click();
		})
	}
	requestUrl('/site/adminrole/getrolelist', 'GET', {current_page: page, page_size: size}, roleListCB)
}
getRoleList(1, 20);
//筛选查询
$('#J_department_select').on('change', function() {
	var pid = $(this).val();
	$('#J_search_input').val('')
	var rid = $('#J_role_select').val();
	getUserList(1, 20, pid, rid);
})
$('#J_role_select').on('change', function() {
	var rid = $(this).val();
	$('#J_search_input').val('')
	var pid = $('#J_department_select').val();
	getUserList(1, 20, pid, rid);
})
$('#J_search_btn').on('click', function() {
	var val = $('#J_search_input').val();
	getUserList(1, 20, $('#J_department_select').val(), $('#J_role_select').val(), val);
})
$('#J_search_input').on('keydown', function(e) {
	var ev = document.all?window.event:e;
    if (ev.keyCode == 13) {
        $('#J_search_btn').click();
    	e.preventDefault();
    }
})
//获取用户列表
var tpl_user = $('#J_tpl_user_list').html();
function getUserList(page, size, did, roleId, keyword) {
	var _data = {
		current_page: page,
		page_size: size,
		department_id: did || '',
		role_id: roleId || '',
		keyword: keyword || ''
	}
	function name(data) {
		var html = '';
		var len = data.length;
		for (var i = 0; i < len; i++) {
			html += data[i].name + ' '
		}
		return html;
	}
	function nameID(data) {
		var id = [];
		var len = data.length;
		for (var i = 0; i < len; i++) {
			id.push(data[i].id)
		}
		return id;
	}
	function userCB(data) {
		juicer.register('name_build', name);
		juicer.register('nameID_build', nameID);
		var html = juicer(tpl_user, data.codes);
		$('#J_user_box').html(html);
		scrolls.forEach(function (scroll) {
		    scroll.refresh();
		})
		//生成分页
		var pageHtml = getPagination(page, Math.ceil(data.total_count/size));
		$('.J_user_page').html(pageHtml);
		$('.J_user_page li').on('click', function() {
			var val = $(this).data('page');
			if (val == undefined) {
				return false
			}
			getUserList(val, size)
		})
		$('.J_user_page #J_page_search input').on('keyup', function() {
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
			if ($(this).val() > $('.J_user_page #J_page_box').data('max')) {
				$(this).val($('.J_user_page #J_page_box').data('max'))
				return false
			}
		})
		$('.J_user_page #J_page_search a').on('click', function() {
			var n = $('.J_user_page #J_page_search input').val();
			if (n > $('.J_user_page #J_page_box').data('max')) {
				alert('已超过最大分页数')
				return false;
			}
			getUserList(n, size)
		})
	}
	requestUrl('/site/adminuser/getuserlist', 'GET', _data, userCB);
}
getUserList(1, 20)

//修改用户弹窗
$('#apxModalManageRoleAdd').on('show.bs.modal', function(e) {
	//重置表单
	$('#apxModalManageRoleAdd input[type*="text"]').val('');
	$('#apxModalManageRoleAdd input[type*="password"]').val('');
	$('#apxModalManageRoleAdd input[type="checkbox"]').prop('checked', false);
	$('#apxModalManageRoleAdd #department').val(-1);
	$('#apxModalManageRoleAdd #J_alert_power_list').html('');

	var $this = $(e.relatedTarget).parents('.J_user');
	if (!$this.hasClass('J_user')) {
		$('#J_user_edit_sure').off().on('click', function() {
			var _data = getAlertData();
			if (_data == undefined) return;
			$('#apxModalManageRoleAdd').modal('hide');
			function addCB(data) {
				getUserList(1, 20);
				$('#J_alert_content').html('成功！');
				$('#apxModalAdminAlert').modal('show');
			}
			requestUrl('/site/adminuser/add', 'POST', _data, addCB)
		})
	} else {
		$('#apxModalManageRoleAdd .modal-title').html('修改用户信息');
		var id = $this.find('.J_user_id').data('id');
		var name = $this.find('.J_user_name').html().trim();
		var department = $this.find('.J_user_department').data('id');
		var mobile = $this.find('.J_user_mobile').html().trim();
		var email = $this.find('.J_user_email').html().trim();
		var role = $this.find('.J_user_role').html().trim();
		$('#apxModalManageRoleAdd #username').val(name).data('id', id);
		$('#apxModalManageRoleAdd #usermobile').val(mobile);
		$('#apxModalManageRoleAdd #useremail').val(email);
		$('#apxModalManageRoleAdd #department').val(department);
		var roleId = $this.find('.J_user_role').data('id').toString().split(',');
		for (var i = 0,len = roleId.length; i < len; i++) {
			$('#J_alert_role_list input[value="' + roleId[i] + '"]').click();
		}
		$('#J_user_edit_sure').off().on('click', function() {
			var _data = getAlertData('edit');
			if (_data == undefined) return;
			$('#apxModalManageRoleAdd').modal('hide');
			function editCB(data) {
				getUserList(1, 20);
				$('#J_alert_content').html('成功！');
				$('#apxModalAdminAlert').modal('show');
			}
			requestUrl('/site/adminuser/modify', 'POST', _data, editCB);
		})
	}
})

//获取弹窗数据
function getAlertData(flag) {
	var id = $('#apxModalManageRoleAdd #username').data('id');
	var name = $('#apxModalManageRoleAdd #username').val().trim();
	var mobile = $('#apxModalManageRoleAdd #usermobile').val().trim();
	var email = $('#apxModalManageRoleAdd #useremail').val().trim();
	var password = $('#apxModalManageRoleAdd #pwd_1').val().trim();
	var confirm_password = $('#apxModalManageRoleAdd #pwd_2').val().trim();
	var departmentId = $('#department').val();
	var roleId = [];
	var len = $('#J_alert_role_list input:checked').length;
	for (var i = 0; i < len; i++) {
		roleId.push($('#J_alert_role_list input:checked').eq(i).val())
	}
	if (name == '' || mobile == '' || email == '') {
		$('#J_alert_content').html('请把信息填写完整！');
		$('#apxModalAdminAlert').modal('show');
		return
	}
	var resultMB = mobile.search(/0?(1)[0-9]{10}/);
	if (resultMB == -1) {
		$('#J_alert_content').html('非法的手机号码！');
		$('#apxModalAdminAlert').modal('show');
		return
	}
	if (departmentId == -1) {
		$('#J_alert_content').html('请选择部门！');
		$('#apxModalAdminAlert').modal('show');
		return
	}
	var resultEM = email.search(/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/);
	if (resultEM == -1) {
		$('#J_alert_content').html('邮箱格式不正确！');
		$('#apxModalAdminAlert').modal('show');
		return
	}
	if (len < 1) {
		$('#J_alert_content').html('请分配角色！');
		$('#apxModalAdminAlert').modal('show');
		return
	}
	var data = {
		password: password,
		confirm_password: confirm_password,
		name: name,
		mobile: mobile,
		email: email,
		department_id: departmentId,
		role_id: roleId,
		status: 1
	}
	if (flag == 'edit') {
		data.id = id;
		if (password != '' || confirm_password != '') {
			if (password.length < 8) {
	    		$('#J_alert_content').html('密码长度应为8-20位！');
				$('#apxModalAdminAlert').modal('show');
	    		return
	    	}
	    	var resultPW = password.toString().search(/[^0-9a-zA-Z]/g);
			if (resultPW != -1) {
				$('#J_alert_content').html('密码只能是数字或字母！');
				$('#apxModalAdminAlert').modal('show');
				return
			}
			if (password !== confirm_password) {
				$('#J_alert_content').html('俩次密码不一致！');
				$('#apxModalAdminAlert').modal('show');
				return
			}
		}
		return data;
	}
	if (password.length < 8) {
		$('#J_alert_content').html('密码长度应为8-20位！');
		$('#apxModalAdminAlert').modal('show');
		return
	}
	var resultPW = password.search(/[^0-9a-zA-Z]/g);
	if (resultPW != -1) {
		$('#J_alert_content').html('密码只能是数字或字母！');
		$('#apxModalAdminAlert').modal('show');
		return
	}
	if (password !== confirm_password) {
		$('#J_alert_content').html('俩次密码不一致！');
		$('#apxModalAdminAlert').modal('show');
		return
	}
	return data;
}

//角色权限联动
$('#apxModalManageRoleAdd').on('click', 'input[type="checkbox"]', function() {
	var flag = $(this).prop('checked');
	var id = $(this).val();
	if (flag) {
		var len = $('#J_alert_power_list .J_role_power[data-id="' + id + '"]').length;
		if (len == 0) {
			function powerListCB(data) {
				if (data.length < 1) return;
				var html = '<ul class="list-unstyled J_role_power" data-id="' + id + '">';
				for (var i = 0,len = data.length; i < len; i++) {
					html += '<li> ' + data[i].name + '</li>'
				}
				html += '</ul>';
				$('#J_alert_power_list').append(html);
			}
			requestUrl('/site/adminrole/getrolepermission', 'GET', {id: id}, powerListCB);
		} else {
			$('#J_alert_power_list .J_role_power[data-id="' + id + '"]').removeClass('hide');
		}
	} else {
		$('#J_alert_power_list .J_role_power[data-id="' + id + '"]').addClass('hide');
	}
})
//删除用户
$('#apxModalAdminDelUser').on('show.bs.modal', function(e) {
	var $this = $(e.relatedTarget).parents('.J_user');
	var id = $this.find('.J_user_id').data('id');
	var name = $this.find('.J_user_name').html().trim();
	var mobile = $this.find('.J_user_mobile').html().trim();
	var department = $this.find('.J_user_department').html().trim();
	$('#apxModalAdminDelUser').data('id', id);
	$('#apxModalAdminDelUser .modal-body .J_name span').html(name);
	$('#apxModalAdminDelUser .modal-body .J_department span').html(department);
	$('#apxModalAdminDelUser .modal-body .J_mobile span').html(mobile);
	$('#apxModalAdminDelUser .J_dele_user').off().on('click', function() {
		$('#apxModalAdminDelUser').modal('hide');
		function deleCB(data) {
			$('#J_alert_content').html('成功！');
			$('#apxModalAdminAlert').modal('show');
			getUserList(1, 20);
		}
		requestUrl('/site/adminuser/remove', 'POST', {id: id}, deleCB)
	})
})

//权限编辑
//添加新角色
$('#apxModalAdminAlertEnterRole').on('show.bs.modal', function() {
	$('#apxModalAdminAlertEnterRole #newName').val('');
})
$('#apxModalAdminAlertEnterRole #J_add_role').on('click', function() {
	var name = $('#apxModalAdminAlertEnterRole #newName').val();
	$('#apxModalAdminAlertEnterRole').modal('hide');
	function addRoleCB(data) {
		$('#J_alert_content').html('成功！');
		$('#apxModalAdminAlert').modal('show');
		getRoleList(1, 20);
	}
	requestUrl('/site/adminrole/add', 'POST',{role_name: name}, addRoleCB)
})
//角色联动
$('#J_role_list').on('click', 'li', function() {
	var $this = $(this);
	$this.addClass('active').siblings('li').removeClass('active');
	//勾选权限
	var id = $this.data('id');
	function rolePowerCB(data) {
		var len = data.length;
		var power = [];
		$('#J_power_list input').prop('checked', false);
		for (var i = 0; i < len; i++) {
			power.push(data[i].id);
			$('#J_power_list input[value="' + data[i].id + '"]').prop('checked', true);
		}
	}
	requestUrl('/site/adminrole/getrolepermission', 'GET', {id: id}, rolePowerCB);
	//关联用户
	function bindUserCB(data) {
		var len = data.length, users = '';
		for (var i = 0; i < len; i++) {
			users += '<li>' + data[i].name + '</li>'
		}
		$('#J_role_users').html(users);
	}
	requestUrl('/site/adminrole/getbinduser', 'GET', {id: id}, bindUserCB)
}).one('click', function() {
	$('#J_power_list input').attr('disabled', false);
})
//获取所有权限列表
function getAllPower() {
	function allPowerCB(data) {
		var tpl_all = $('#J_tpl_allPower').html();
		var html = juicer(tpl_all, data);
		$('#J_power_list').html(html);
		$('#J_power_list input').attr('disabled', true);
		scrolls.forEach(function (scroll) {
		    scroll.refresh();
		})
	}
	requestUrl('/site/adminrole/getpermissionlist', 'GET', '', allPowerCB)
}
getAllPower()

//角色权限绑定和取消
$('#J_power_list').on('click', 'input', function() {
	var $this = $(this);
	var flag = $(this).is(':checked');
	var powerId = $(this).val();
	if (flag) {
		var id = $('#J_role_list li[class*="active"]').data('id');
		if (id == undefined) return
		requestUrl('/site/adminrole/bindpermission', 'POST', {id: id, permission_id: powerId}, function(data) {
			$('#J_alert_content').html('权限分配成功！');
			$('#apxModalAdminAlert').modal('show');
		}, function(data) {
			$('#J_alert_content').html(data.data.errMsg);
    		$('#apxModalAdminAlert').modal('show');
		}, false, function(data) {
			$('#J_alert_content').html('操作失败，请确认您是否有权限！');
    		$('#apxModalAdminAlert').modal('show');
    		$this.prop('checked', false);
		})
	} else {
		var id = $('#J_role_list li[class*="active"]').data('id');
		if (id == undefined) return
		requestUrl('/site/adminrole/revokepermission', 'POST', {id: id, permission_id: powerId}, function(data) {
			$('#J_alert_content').html('权限取消成功！');
			$('#apxModalAdminAlert').modal('show');
		}, function(data) {
			$('#J_alert_content').html(data.data.errMsg);
    		$('#apxModalAdminAlert').modal('show');
		}, false, function(data) {
			$('#J_alert_content').html('操作失败，请确认您是否有权限！');
    		$('#apxModalAdminAlert').modal('show');
    		$this.prop('checked', false);
		})
	}
})
//删除角色
$('#apxModalAdminDelRole').on('show.bs.modal', function(e) {
	var $this = $(e.relatedTarget).parents('.J_role');
	var id = $this.data('id');
	$('#apxModalAdminDelRole').data('id', id);
	var name = $this.find('.J_role_name').html();
	$('#apxModalAdminDelRole .J_dele_name').html(name);
})
$('#apxModalAdminDelRole .J_dele_role').on('click', function(e) {
	var id = $('#apxModalAdminDelRole').data('id');
	$('#apxModalAdminDelRole').modal('hide');
	if($('#J_role_users li').length < 1) {
		function deleRoleCB(data) {
			$('#J_alert_content').html('删除成功！');
			$('#apxModalAdminAlert').modal('show');
			getRoleList(1, 20);
		}
		requestUrl('/site/adminrole/delete', 'POST', {id: id}, deleRoleCB);
	} else {
		$('#apxModalAdminAlertDisable').modal('show');
	}
})

// Refresh the scrolls when the new tab actived,
// for the reason that the list been loaded once the page loaded
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	scrolls.forEach(function (scroll) {
		scroll.refresh();
	})
});








})