$(function() {
	//新建弹窗
	buildNewAlertInHere()
	var _id = url('?id'),
	    _tpl = $('#J_tpl_list').html(),
	    _page = 1,
	    _size = 10,
	    _pageBox = $('#J_qr_page');

	//获取备注
	function getRemark(id) {
		requestUrl('/site/promoter/code-title', 'GET', {id: id}, function(data) {
			$('#J_remark').text(data.title)
		})
	}
	getRemark(_id)
	$('#J_invite_id').text(_id)
	//修改备注
	$('#J_edit_btn').on('click', function() {
		var remark = $('#J_new_remark').val().trim();
		if (remark == '') {
			showAlert('新备注不能为空！')
			return
		}
		$('#apxModalBusinessEdit').modal('hide');
		requestUrl('/site/promoter/update', 'POST', {id: _id, title: remark}, function(data) {
			showAlert('修改成功！')
			$('#J_remark').text(remark)
		})
	})
	//获取邀请记录
	//计算状态
	function status(data) {
        if (data == 1) {
            return '待提交'
        }
        if (data == 2) {
            return '待审核'
        }
        if (data == 3) {
            return '审核失败'
        }
        if (data == 4) {
            return '待开通'
        }
        if (data == 5) {
            return '已开通'
        }
    }
    juicer.register('status', status)

	function getInviteLog(page, size, id, status, search) {
		var _data = {
			current_page: page || _page,
			page_size: size || _size,
			id: id,
			status: status || '1,3',
			search_text: search || ''
		}
		requestUrl('/site/promoter/invite-log', 'GET', _data, function (data) {
			$('#J_qr_list').html(juicer(_tpl, data))
			if (data.data.length > 1) {
				pagingBuilder.build(_pageBox, page, size, data.total_count);
				pagingBuilder.click(_pageBox, function(page) {
					getQrList(page, size, id, status, search)
				})
			}
		})
	}
	getInviteLog(_page, _size, _id)
	//切换查询状态
	$('#J_qr_status').on('click', 'span', function () {
		var status = $(this).data('id');
		$(this).addClass('active').siblings('span').removeClass('active');
		var val = $('#J_search_input').val().trim();
		getInviteLog(_page, _size, _id, status, val)
	})

	//搜索
	$('#J_search_btn').on('click', function() {
		var val = $('#J_search_input').val().trim();
		var status = $('#J_qr_status span[class*="active"]').data('id');
		getInviteLog(_page, _size, _id, status, val);
	})

	//下载二维码链接
	$('#J_download').attr('href', '/site/promoter/download?id=' + _id)
	//返回上级
	$('#J_back').on('click', function() {
		window.history.back()
	})
})