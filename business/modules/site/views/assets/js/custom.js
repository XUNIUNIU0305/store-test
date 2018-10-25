$(function() {
	//创建提示弹窗
	buildNewAlertInHere();
	//获取注册码列表
	var tpl_list = $('#J_tpl_list').html();
	var list_compile = juicer(tpl_list);
	//juicer自定义函数
	function linkArea(data) {
		var area = '';
		$.each(data, function(v, k) {
			area += k + " "
		})
		return area;
	}
	function userStatus(data) {
		if (data === 0) {
			return 'text-danger'
		}
		return 'text-success'
	}
	function userStatusText(data) {
		if (data === 0) {
			return '未激活'
		}
		return '已激活'
	}
	juicer.register('linkArea', linkArea);
	juicer.register('userStatus', userStatus);
	juicer.register('userStatusText', userStatusText);
	function getUserList(page, size, registered) {
		var data = {
			current_page: page,
			page_size: size,
			registered: registered || '-1'
		}
		requestUrl('/site/custom/registercode-list', 'GET', data, function(data) {
			var html = list_compile.render(data);
			$('#J_user_list').html(html);
			pagingBuilder.build($('#J_user_page'), page, size, data.total_count);
			pagingBuilder.click($('#J_user_page'), function(page) {
				getUserList(page, size, registered);
			});
		})
	}
	getUserList(1, 12);
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
	//数量输入
	$('.J_input_minus').on('click', function() {
		var val = $('#J_user_count').val();
		if (val < 2) {return};
		$('#J_user_count').val(val - 1);
	})
	$('.J_input_add').on('click', function() {
		var val = $('#J_user_count').val();
		if (val > 99) {return};
		$('#J_user_count').val(val - 0 + 1);
	})
	$('#J_user_count').on('keyup', function() {
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
            if ($(this).val() > 99) {
                $(this).val(100)
                return false
            }
	})
	//弹窗
	$('#apxModalBusinessAdd').on('shown.bs.modal', function() {
		$('.user_count').html($('#J_user_count').val());
	})
	$('#J_sure_add').on('click', function() {
		$('#apxModalBusinessAdd').modal('hide');
		var len = $('select.J_area_box').length;
		for (var i = 0; i < len; i++) {
			var val = $('select.J_area_box').eq(i).val();
			if (val === '-1') {
				showAlert('请选择完整的归属信息！');
				return;
			}
		}
		var area_id = $('select.J_area_box').eq(len - 1).val();
		requestUrl('/site/custom/registercode', 'POST', {area_id: area_id, number: $('#J_user_count').val()}, function(data) {
			getUserList(1, 12)
		})
	})
	//状态切换
	$('#J_user_status').on('change', function() {
		var val = $(this).val();
		getUserList(1, 12, val);
	})
})
