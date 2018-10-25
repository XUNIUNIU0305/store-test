$(function() {
	var _page = 1,
		_size = 12;
	//新增弹窗
	buildNewAlertInHere()
	//获取二维码列表
	var tpl_list = $('#J_tpl_list').html(),
		_pageBox = $('#J_store_page');
	function getQrList(page, size) {
		var page = page || _page,
			size = size || _size;
		var _data = {
			current_page: page,
			page_size: size
		}
		requestUrl('/site/promoter/list', 'GET', _data, function(data) {
			$('#J_store_list').html(juicer(tpl_list, data.data));
			//生成分页
			if (data.data.length > 1) {
				pagingBuilder.build(_pageBox, page, size, data.total_count);
				pagingBuilder.click(_pageBox, function(page) {
					getQrList(page, size)
				})
			}
		})
	}
	getQrList()
	//新增邀请码
	function addQR() {
		requestUrl('/site/promoter/add', 'POST', '', function(data) {
			showAlert('添加成功！')
			getQrList()
		})
	}
	//获取统计数据
	function getStatistics() {
		requestUrl('/site/promoter/count', 'GET', '', function(data) {
			$('#J_in_use').html(data.apply_code)
			$('#J_success_use').html(data.success_register)
			$('#J_check_pending').html(data.wait_auth)
			$('#J_wait_submit').html(data.wait_submit)
            $('#J_amount').html('￥' + (data.amount - 0 ).toFixed(2))
		})
	}
	getStatistics()

	//点击添加二维码
	$('#J_add_qr').on('click', function() {
		$('#apxModalBusinessAdd').modal('hide')
		addQR()
	})
	//禁用邀请码
	function delQR(id) {
		requestUrl('/site/promoter/delete', 'POST', {id: id}, function(data) {
			showAlert('禁用成功！')
			getQrList($('#J_store_page li[class*="active"]').data('page'))
		})
	}
	//保存所点击邀请码的ID
	$('#apxModalBusinessDel').on('show.bs.modal', function(e) {
		var $this = $(e.relatedTarget)
		$(this).data('id', $this.data('id'))
	})
	$('#J_del_qr').on('click', function() {
		$('#apxModalBusinessDel').modal('hide')
		var id = $('#apxModalBusinessDel').data('id')
		delQR(id)
	})

	//修改备注
	$('#J_store_list').on('click', '.edit-remark', function() {
		var $this = $(this);
		$this.parents('.item').find('.remark p').addClass('hidden').siblings('input').removeClass('hidden')
		var id = $this.data('id')
		$this.text('确定修改')
		$this.off().one('click', function() {
			var remark = $this.parents('.item').find('.remark input').val().trim()
			if (remark == '') {
				showAlert('新备注不能为空！')
				return
			}
			requestUrl('/site/promoter/update', 'POST', {id: id, title: remark}, function(data) {
				showAlert('修改成功！')
				$this.text('修改备注')
				$this.parents('.item').find('.remark input').addClass('hidden').siblings('p').removeClass('hidden')
				getQrList($('#J_store_page li[class*="active"]').data('page'))
			})
		})
	})

})