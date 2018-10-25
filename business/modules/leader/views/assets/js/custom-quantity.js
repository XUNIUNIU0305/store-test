$(function() { 
	// 判断用户身份
	var USER_ROLE = '';
	requestUrl('/main/user-info', 'GET', '', function(data) {
		USER_ROLE = data.role;
	}, function(data) {
		alert(data.errMsg)
	}, false)


	//获取门店数量
	var tpl = $('#J_tpl_list').html();
	var compaile = juicer(tpl);
	function getCusotmCount(id, dom) {
		requestUrl('/leader/custom-quantity/list', 'GET', {parent_id: id}, function(data) {
			var html = compaile.render({data: data});
			dom.html(html);
		})
	}
	getCusotmCount(0, $('#J_list_box'))

	// 获取门店
	function getCustomList(id, dom) {
		var _page = dom.data('page');
		var _data = {
			current_page: _page,
			page_size: 12,
			area_id: id
		}
		dom.data('page', _page - 0 + 1);
		requestUrl('/leader/custom-list/list', 'GET', _data, function(data) {
			var _dom = ''
			$.each(data.list, function(i, val) {
				var _area = '',_isShow = '';
				var _name = '【' + val.shop_name + '(' + val.nick_name + ')' + '】'; 
				if (val.shop_name === '') {
					_isShow = 'hidden'
				}
				$.each(val.area, function(index, value) {
					_area += value.id + ','
				})
				dom.data('area', _area);
				_dom += `<li>
							<div class="content"><i></i>
								<span class="J_show_info" data-account="` + val.account + `">` + val.account + `</span>
								<span class="name-box ` + _isShow + `" style="display: inline-block;max-width: 300px;overflow: hidden;vertical-align: top; text-overflow:ellipsis;white-space: nowrap;" title="` + _name + `">` + _name + `</span>
								<span class="btn-edit J_area_edit ` + (USER_ROLE === '超级管理员' ? '' : 'hidden') + `" data-account="` + val.account + `" data-area="` + _area + `">修改</span>
								<div class="area-box J_info_area"></div>
							</div>
						</li>`
			})
			if (_page * 12 <= data.total_count) {
				_dom += `<li>
							<div class="content"><i></i>
								<span class="btn-edit J_add_more" data-id="` + id + `">加载更多</span>
							</div>
						</li>`
			}
			if (_page == 1 && dom.find('li').length > 0) {
				return
			}
			dom.append(_dom);
		})
	}
	$('#J_list_box').on('click', '.J_add_more', function(e) {
		e.stopPropagation();
		var id = $(this).data('id');
		var dom = $(this).parents('.branch').eq(0);
		getCustomList(id, dom);
		$(this).parent('.content').parent('li').remove();
	})

	$('.business-branches-container').on('click', '.content', function (e) { 
	    e.preventDefault(); 
	    var id = $(this).find('.branch-box').data('id');
		if (!id) return;
		var level = $(this).data('level');
		var $branch = $(this).siblings('.branch');
		if (level != 5) {
			getCusotmCount(id, $branch);
		} else {
			if ($branch.hasClass('opened')) {
				$branch.html('')
				$branch.data('page', 1)
			} else {
				getCustomList(id, $branch)
			}
		}
	    $branch.length && $branch.toggleClass('opened');
	}) 

	// 修改所属区域
	$('#J_list_box').on('click', '.J_area_edit', function() {
		getArea(1, 0, $(this).siblings('.J_info_area'));
		var _area = $(this).data('area').split(',');
		for (var i = 0; i < 4; i++) {
			getArea(i + 2, _area[i], $(this).siblings('.J_info_area'));
			$(this).siblings('.J_info_area').find('select.J_area_box').eq(i).val(_area[i]);
		}
		$(this).siblings('.J_info_area').find('select.J_area_box').eq(4).val(_area[4]);
		$('.selectpicker').selectpicker('refresh');
		$('.selectpicker').selectpicker('show');
		$(this).text('确定').addClass('J_edit_sure').removeClass('J_area_edit');
	})
	// 确定修改
	$('#J_list_box').on('click', '.J_edit_sure', function() {
		$(this).css('pointer-events', 'none');
		var area_id = $(this).siblings('.J_info_area').find('select.J_area_box:last').val();
		var account = $(this).data('account');
		var _html = $(this).siblings('.name-box').html();
		var _title = $(this).siblings('.name-box').attr('title');
		var _class = $(this).siblings('.name-box').attr('class');
		if (area_id === '-1') {
			alert('请选择正确区域！');
			return;
		}
		var data = {
			account: account,
			area_id: area_id
		}
		var _this = $(this);
		if ($(this).data('change')) {
			return
		}
		$(this).data('change', true)
		requestUrl('/leader/custom/area', 'POST', data, function(data) {
			_this.data('change', false)
			alert('修改成功！');
			_this.parent('.content').parent('li').remove();
			var _parent = $('.branch-box[data-id="' + area_id + '"]');
			if (_parent.parent('.content').siblings('.branch').hasClass('opened')) {
				var _area = _parent.parent('.content').siblings('.branch').data('area');
				var _dom = `<li>
							<div class="content"><i></i>
								<span class="J_show_info" data-account="` + account + `">` + account + `</span>
								<span class="` + _class + `" style="display: inline-block;max-width: 300px;overflow: hidden;vertical-align: top; text-overflow:ellipsis;white-space: nowrap;" title="` + _title + `">` + _html + `</span>
								<span class="btn-edit J_area_edit" data-account="` + account + `" data-area="` + _area + `">修改</span>
								<div class="area-box J_info_area"></div>
							</div>
						</li>`
				_parent.parent('.content').siblings('.branch').prepend(_dom);
			}
		})
	})

	// tip显示用户信息
	var _accountInfo = {};
	$('#J_list_box').on('mouseenter.show_info', '.J_show_info', function(e) {
		var ele = $(this);
		var id = $(this).data('account');
		ele.data('cd', true);
		var _timer = setTimeout(function() {
			if (ele.data('cd')) {
				getInfo(id, ele);
			} else {
				clearTimeout(_timer)
			}
		}, 200);
	}).on('mouseleave.show_info', '.J_show_info', function(e) {
		$(this).data('cd', false);
		$(this).popover('destroy');
	})
	function getInfo(id, dom) {
		if (_accountInfo[id]) {
			dom.popover({
				tirgger: 'manual',
				placement: 'top',
				html: true,
				content: setInfo(_accountInfo[id])
			});
			dom.popover('show');
			dom.siblings('.popover').one('mouseleave', function() {
				dom.popover('destroy');
			})
			$('.J_show_info').one('show.bs.popover', function() {
				$('.J_show_info').not(this).popover('destroy');
			})
			return
		}
		requestUrl('/leader/custom/info', 'GET', {account: id}, function(data) {
			_accountInfo[id] = data;
			dom.popover({
				tirgger: 'manual',
				placement: 'top',
				html: true,
				content: setInfo(data)
			});
			dom.popover('show');
			dom.siblings('.popover').one('mouseleave', function() {
				dom.popover('destroy');
			})
			$('.J_show_info').one('show.bs.popover', function() {
				$('.J_show_info').not(this).popover('destroy');
			})
		})
	}
	function setInfo(data) {
		var _html = '<div class="popover-box">';
		_html += `<p><span>账户ID：</span>` + data.account + `</p>`;
		_html += `<p><span>账户名称：</span>` + data.nick_name + `</p>`;
		_html += `<p><span>手机号：</span>` + data.mobile + `</p>`;
		_html += `<p><span>邮箱：</span>` + data.email + `</p>`;
		_html += '</div>';
		return _html;
	}
	
	var levels = {};
    function getLevel() {
        requestUrl('/leader/area/level', 'GET', '', function(data) {
            levels = data;
        }, function(data) {
            alert(data.data.errMsg)
        }, false)
    }
    getLevel();
    //获取区域列表
    function getArea(level, id, dom) {
        requestUrl('/leader/area/list', 'GET', {parent_id: id}, function(data) {
            var html = '<div class="select-box">\
                            <select class="selectpicker J_area_box btn-group-xs" data-width="100%" data-haschild="' + data.has_child + '" data-level="' + level + '">\
                                <option value="-1">请选择' + levels[level] + '</option>';
            for (var i = 0; i < data.list.length; i++) {
                html += '<option value="' + data.list[i].id + '">' + data.list[i].name + '</option>'
            }
            html += '</select>\
				</div>'
			dom.append(html);
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker('show');
        }, function(data) {
            alert(data.data.errMsg);
        }, false)
    }
    //区域联动
    $('#J_list_box').on('change', 'select.J_area_box', function() {
        if (!$(this).data('haschild')) {return};
        var level = $(this).data('level') - 0;
        var val = $(this).val() - 0;
        if (val === -1) {return};
        $(this).parents('.J_info_area').find('select.J_area_box:gt(' + (level - 1) + ')').parents('.select-box').remove();
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('show');
        getArea(level + 1, val, $(this).parents('.J_info_area'));
	})
})