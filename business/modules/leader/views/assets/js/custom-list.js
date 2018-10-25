$(function() {
	//创建提示弹窗
	buildNewAlertInHere();
	//获取层级列表
	var levels = {};
	function getLevel() {
		requestUrl('/leader/area/level', 'GET', '', function(data) {
			levels = data;
		}, function(data) {
			showAlert(data.data.errMsg)
		}, false)
	}
	getLevel();
	//获取区域列表
	function getArea(level, id) {
		requestUrl('/leader/area/list', 'GET', {parent_id: id}, function(data) {
			var html = '<div class="col-xs-2">\
                            <select class="selectpicker J_area_box btn-group-xs" data-width="110%" data-haschild="' + data.has_child + '" data-level="' + level + '">\
                                <option value="-1">请选择' + levels[level] + '</option>';
            for (var i = 0; i < data.list.length; i++) {
            	html += '<option value="' + data.list[i].id + '">' + data.list[i].name + '</option>'
            }
            html += '</select>\
                </div>'
			$('#J_select_box').append(html);
			$('.selectpicker').selectpicker('refresh');
			$('.selectpicker').selectpicker('show');
		})
	}
	getArea(1, 0);
	//区域联动
	$('#J_select_box').on('change', 'select.J_area_box', function() {
		if (!$(this).data('haschild')) {return};
		var level = $(this).data('level') - 0;
		var val = $(this).val() - 0;
		if (val === -1) {return};
		$('select.J_area_box:gt(' + (level - 1) + ')').parents('.col-xs-2').remove();
		$('.selectpicker').selectpicker('refresh');
		$('.selectpicker').selectpicker('show');
		getArea(level + 1, val);
	})

	//获取注册码列表
	var tpl_list = $('#J_tpl_list').html();
	var list_compile = juicer(tpl_list);
	//juicer自定义函数
	function linkArea(data) {
		var area = '';
		$.each(data, function(v, k) {
			area += k.name + " "
		})
		return area;
	}
	juicer.register('linkArea', linkArea);
	function getUserList(page, size, type, search) {
		var data = {
			current_page: page,
			page_size: size
		}
		cache.type = type;
		cache.search = search;
		if (type === 'account') {
			data['account'] = search;
		} else if (type === 'area') {
			data['area_id'] = search;
		}
		requestUrl('/leader/custom-list/list', 'GET', data, function(data) {
			var html = list_compile.render(data);
			$('#J_user_list').html(html);
			pagingBuilder.build($('#J_user_page'), page, size, data.total_count);
			pagingBuilder.click($('#J_user_page'), function(page) {
				getUserList(page, size, type, search);
			});
		})
	}
	var cache= {};
	//按账号查询
	$('#J_search_account').on('click', function() {
		var val = $('#J_search_input').val().trim();
		if (val.search(/[^0-9]/g) != -1) {
			showAlert('账号格式错误！');
			return;
		}
		
		getUserList(1, 12, 'account', val);
	})
	$('#J_search_input').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('#J_search_account').click();
		}
	})
	//按区域查询
	$('#J_search_area').on('click', function() {
		var id = $('#J_select_box select.J_area_box:last').val();
		if (id === '-1') {
			showAlert('请选择正确区域！');
			return;
		}
		getUserList(1, 12, 'area', id);
	})
	//记录分页
	cache.page = 1;
	$('#J_user_page').on('click', 'li', function() {
		cache.page = $(this).data('page');
	})
	//跳转账户详情页
	$('#J_user_list').on('click', '.account_detail', function() {
		var id = $(this).data('account');
		window.location.href = '/leader/custom?show=1&account=' + id + '&type=' + cache.type + '&search=' + cache.search + '&page=' + cache.page;
	})
	//页面带参初始化
	function initPage() {
		if (url('?type')) {
			getUserList(url('?page'), 12, url('?type'), url('?search'));
		}
	}
	initPage()
})