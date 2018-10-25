$(function() {
    var nowDate = new Date(),
    _Month = (nowDate.getMonth() + 1);
    _day = nowDate.getDate();
    if (_Month < 10) {
        _Month = '0' + _Month
    }
    if (_day < 10) {
        _day = '0' + _day
    }
    var nowTime = nowDate.getFullYear() + '-' + _Month + '-' + _day;
    $('input.J_search_timeStart').val(nowTime);
    $('input.J_search_timeEnd').val(nowTime);
    // add locale
    $.fn.datepicker.dates["zh-CN"] = {
        days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
        daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
        daysMin: ["日", "一", "二", "三", "四", "五", "六"],
        months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        monthsShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
        today: "今日",
        clear: "清除",
        format: "yyyy年mm月dd日",
        titleFormat: "yyyy年mm月",
        weekStart: 1
    }
    // init the datepicker
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        orientation: 'bottom'
    });
    $('.J_timeStart_show').on('click', function() {
        $('.J_search_timeStart').datepicker('show')
    })
    $('.J_timeEnd_show').on('click', function() {
        $('.J_search_timeEnd').datepicker('show')
    })

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
	//提示弹窗
	buildNewAlertInHere()
	//获取层级列表
	var levels = {};
	function getLevel() {
		requestUrl('/leader/area/level', 'GET', '', function(data) {
			levels = data
		}, function(data) {
			showAlert(data.data.errMsg)
		}, false)
	}
	getLevel()
	//设置列表模板
	var tpl_area = $('#J_tpl_area_list').html();
	var area_compiled = juicer(tpl_area);
	//获取层级名称
	function getAreaTitle(level) {
		if (!levels[level]) {return};
		if (level === 1) {
			var tabs = '<li role="presentation" class="active"><a href="#J_area_content_' + level + '" role="tab" data-toggle="tab">' + levels[1] + '</a></li>'
			$('#J_tab_list').append(tabs);
			var tabContent = '<div role="tabpanel" data-level="' + level + '" class="tab-pane active" id="J_area_content_' + level + '">\
	                            <div class="iscorll_container">\
	                                <ul class="list-unstyled dashed-split J_area_big" id="J_area_' + level + '" data-level="' + level + '"></ul>\
	                            </div>\
	                        </div>'
	        $('#J_tab_content').append(tabContent);
		} else {
			var tabs = '<li role="presentation"><a href="#J_area_content_' + level + '" role="tab" data-toggle="tab">' + levels[level] + '</a></li>'
			$('#J_tab_list').append(tabs);
			var tabContent = '<div role="tabpanel" data-level="' + level + '" class="tab-pane" id="J_area_content_' + level + '">\
	                            <div class="iscorll_container">\
	                                <ul class="list-unstyled dashed-split J_area_big" id="J_area_' + level + '" data-level="' + level + '"></ul>\
	                            </div>\
	                        </div>'
	        $('#J_tab_content').append(tabContent);
		}
	}
	//获取区域列表
	function getAreaList(level, id) {
		requestUrl('/leader/area/list', 'GET', {parent_id: id}, function(data) {
			var html = area_compiled.render(data);
			$('#J_area_' + level).html(html);
			refreshScroll();
		})
	}
	getAreaTitle(1);
	getAreaList(1, 0);
	//获取下一级
	$('#J_tab_content').on('click', '.J_area_box', function() {
		var $this = $(this);
		$this.parents('.J_area_big').find('.J_area_box').removeClass('active');
		$this.addClass('active');
		var id = $this.data('id');
		var level = $this.parents('.J_area_big').data('level') - 0;
		//底部显示
		for (var i = $('#J_tab_footer span').length; i >= level; i--) {
			$('#J_tab_footer span').eq(i).html('')
		}
		var name = $this.data('name');
		$('#J_tab_footer span').eq(level - 1).html(name + '<i class="glyphicon glyphicon-chevron-right"></i>');
		getRole(id);
		if ($(this).data('haschild') === false) {return};
		//删除下级以外层级
		for (var i = $('#J_tab_list li').length; i >= level ; i--) {
			$('#J_tab_list li').eq(i).remove();
			$('#J_tab_content .tab-pane').eq(i).remove();
		}
		getAreaTitle(level + 1);
		getAreaList(level + 1, id);
	})
	//获取区域角色 
	function getRole(id) {
		requestUrl('/leader/area/role', 'GET', {area_id: id}, function(data) {
			var len = data.length;
			var html = '', name = '';
			for (var i = 0; i < len; i++) {
				if (data[i].user !== false) {
					name = data[i].user.name;
					html += '<p><span class="head col-xs-5">' + data[i].name + '：&nbsp;<span class="name">' + name + '</span></span><a href="/account/index?id=' + data[i].user.id + '"><small class="col-xs-3">查看历史业绩</small></a><a class="btn col-xs-2" data-id="' + data[i].id + '" data-toggle="modal" data-target="#apxModalManageStore">修改</a></p>'
				} else {
					name = '';
					html += '<p><span class="head col-xs-5">' + data[i].name + '：&nbsp;<span class="name">' + name + '</span></span><a href=""><small class="col-xs-3 invisible">查看历史业绩</small></a><a class="btn col-xs-2" data-id="' + data[i].id + '" data-toggle="modal" data-target="#apxModalManageStore">修改</a></p>'
				}
			}
			$('#J_leader_box').html(html);
		})
	}
	//新增区域
	$('#apxModalBusinessAdd').on('show.bs.modal', function(e) {
		$('#J_new_area').val('');
		var $this = $('#J_tab_list li[class="active"]');
		$('#apxModalBusinessAdd .area_name').html($('#J_tab_list li[class="active"] a').html());
		var level = $('#J_tab_content .tab-pane[class*="active"]').data('level');
		if (level === 1) {
			var id = 0;
		} else {
			var id = $('#J_area_content_' + (level - 1)).find('.J_area_box[class*="active"]').data('id');
		}
		$('#J_add_btn').off().on('click', function() {
			var name = $('#J_new_area').val().trim();
			if (name == '') {
				showAlert('不能为空！');
				return;
			}
			$('#apxModalBusinessAdd').modal('hide');
			requestUrl('/leader/area/add', 'POST', {parent_id: id, name: name}, function(data) {
				showAlert('添加成功！');
				if (level === 1) {
					getAreaList(1 ,0);
					$('#J_tab_list li:gt(0)').remove();
				} else {
					$('#J_tab_list li').eq(level - 2).find('a').click();
					$('#J_area_content_' + (level - 1)).find('.J_area_box[class*="active"]').click();
					$('#J_tab_list li').eq(level - 1).find('a').click();
				}
			})
		})
	})
	//获取用户列表
	var tpl = $('#J_tpl_account').html();
	var list_compaile = juicer(tpl);
	function getUser(page, size, user) {
		var data= {
			current_page: 1,
			page_size: 9999,
			user: user || ''
		}
		requestUrl('/leader/person/list', 'GET', data, function(data) {
			var html = list_compaile.render(data.data);
			$('#J_account_list').html(html);
			refreshScroll();
		})
	}
	//搜索
	$('#J_search_btn').on('click', function() {
		var user = $('#J_search_input').val();
		getUser(1, 9999, user);
	})
	$('#J_search_input').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('#J_search_btn').click();
		}
	})
	//修改区域负责人
	$('#apxModalManageStore').on('shown.bs.modal', function(e) {
		var $this = $(e.relatedTarget);
		$('#J_replace_sure').data('role', $this.data('id'));
		getUser();
	})
	$('#J_account_list').on('click', 'li', function() {
		$('#J_account_list li').removeClass('active');
		$(this).addClass('active');
	})
	$('#J_replace_sure').on('click', function() {
		var area_id = $('.tab-pane[class*="active"]').find('.J_area_box[class*="active"]').data('id');
		var user_id = $('#J_account_list li[class*="active"]').data('id');
		var role = $(this).data('role');
		var data = {
			area_id: area_id,
			user_id: user_id,
			role: role
		}
		if (user_id != undefined) {
			$('#apxModalManageStore').modal('hide');
			requestUrl('/leader/area/appoint', 'POST', data, function(data) {
				showAlert('修改成功！');
				$('.tab-pane[class*="active"]').find('.J_area_box[class*="active"]').click();
			})
		}
	})
	//修改区域名称
	$('#apxModalBusinessEdit').on('show.bs.modal', function(e) {
		var $this = $(e.relatedTarget);
		$(this).data('id', $this.data('id'));
		$('#J_new_area_name').val($this.data('name'));
	})
	$('#J_edit_btn').on('click', function() {
		var id = $('#apxModalBusinessEdit').data('id');
		var name = $('#J_new_area_name').val();
		if (name.length < 1 || name.length > 20) {
			showAlert('名称长度为1到20位！');
			return;
		}
		var data = {
			area_id: id,
			name: name
		}
		var level = $('#J_tab_content .tab-pane[class*="active"]').data('level');
		$('#apxModalBusinessEdit').modal('hide');
		requestUrl('/leader/area/modify', 'POST', data, function(data) {
			showAlert('修改成功！');
			$('#J_new_area_name').val('');
			if (level === 1) {
				getAreaList(1 ,0);
				$('#J_tab_list li:gt(0)').remove();
			} else {
				$('#J_tab_list li').eq(level - 2).find('a').click();
				$('#J_area_content_' + (level - 1)).find('.J_area_box[class*="active"]').click();
				$('#J_tab_list li').eq(level - 1).find('a').click();
			}
		})
	})

	//获取用户业绩
    var _tpl = juicer($('#J_tpl_data').html());
    function money(data) {
        return (data.normal + data.refund + data.reject).toFixed(2)
    }
    juicer.register('money', money);
    function getChart(id, start, end, type) {
    	var data = {
    		area_id: id,
    		date_from: start,
    		date_to: end,
    		date_type: type
    	}
    	requestUrl('/leader/area/chart', 'GET', data, function(data) {
    		var html = _tpl.render(data);
            $('#J_user_data').html(html);
    	})
    }
    $('#date_tab span').on('click', function() {
       $('#date_tab span').removeClass('active'); 
       $(this).addClass('active'); 
    })
    $('#J_search_data').on('click', function() {
        var start = $('input.J_search_timeStart').val();
        var end = $('input.J_search_timeEnd').val();
        var type = $('#date_tab span[class*="active"]').data('id');
        var id = $('.tab-pane[class*="active"]').find('.J_area_box[class*="active"]').data('id');
        if (id == '' || id == undefined) return;
        getChart(id, start, end, type);
    })
})
