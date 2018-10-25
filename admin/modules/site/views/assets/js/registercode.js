$(function() {
	//加减
	$('.apx-admin-add-account .sub').on('click', function() {
		var val = $(this).siblings('input').val() - 0;
		if (val < 100) $(this).siblings('input').val(val + 1);
	})
	$('.apx-admin-add-account .add').on('click', function() {
		var val = $(this).siblings('input').val() - 0;
		if (val > 1) $(this).siblings('input').val(val - 1);
	})

	//限制数量
	$('#J_account_nub').on('keyup', function() {
    	var number = $(this).val().replace(/\D/g,'') - 0;
    	if (number > 100) {
    		number = 100
    	}
    	$(this).val(number);
    }).on('focus', function() {
    	$(this).select();
    })
	//添加注册码
	function addList(nub) {
		var data = {
			quantity: nub
		}
		function addCB(data) {
			$('#modalAddType .sr-only').click();
			getList();
		}
		requestUrl('/site/registercode/create-custom', 'POST', data, addCB)
	}
	//获取注册码列表
	function getList() {
		var data = {
			current_page: 1,
			page_size: 99999
		}
		function listCB(data) {
			var len = data.codes.length;
			var result = '';
			for (var i = 0; i < len; i++) {
				if (i%2 == 0) {
					result += '<li class="account-list">' + data.codes[i].account + '</li>'
				} else {
					result += '<li class="account-list odd">' + data.codes[i].account + '</li>'
				}
			}
			$('.J_account_box').html(result);
		}
		requestUrl('/site/registercode/get-custom', 'GET', data, listCB)
	}
	if ($('.apx-admin-account').length > 0) getList()

	//绑定注册事件
	$('.apx-admin-add-account .J_add_account').on('click', function() {
		var nub = $('#J_account_nub').val();
		if (nub < 1) {
			alert('至少添加一个！')
			return
		}
		addList(nub);
	})



















})