$(function() {
	//获取用户信息
	function getInfo() {
		requestUrl('/main/user-info', 'GET', '', function(data) {
			$('#J_user_name').html(data.account + '&nbsp;&nbsp;' + data.name)
		})
	}
	getInfo()
	//获取菜单
	var tpl_menu = $('#J_tpl_menu').html();
	function getMenu() {
		requestUrl('/main/menu', 'GET', '', function(data) {
			var html = juicer(tpl_menu, data);
			$('#J_menu_list').html(html);
		})
	}
	getMenu()
	var tagBox = {};
	//点击打开iframe页
	$('#J_menu_list').on('click', '.J_menu_url', function() {
		var dom = $('#J_menu_list .J_menu_url[class*="active"]');
		$(this).addClass('active');
		var id = $(this).data('id');
		var url = $(this).data('url');
		$('.J_menu_url[data-id!="' + id + '"]').removeClass('active');
		//标签页效果
		$('#J_tag_list li').removeClass('active');
		$('iframe').removeClass('active');
		//新增新标签页
		if (!tagBox[id]) {
			if ($('#J_tag_list li').length >= 6) {
				alert('无法打开更多页面！');
				dom.click();
				return
			};
			tagBox[id] = id;
			var tag = '<li class="active" data-url="' + url + '">' + $(this).find('a').html() + ' <span class="remove-btn">&times;</span></li>'
			$('#J_tag_list').append(tag);
			var iframe = '<iframe class="active" data-url="' + url + '" src="' + url + '" frameborder="0" width="98%" height="100%" marginheight="0" marginwidth="0"></iframe>'
			$('.business-frame-main').append(iframe);
		} else {
			//选择已存在标签页
			$('#J_tag_list li[data-url="' + url + '"]').addClass('active');
			$('iframe[data-url="' + url + '"]').addClass('active');
		}
	})
	$('#J_tag_list').on('click', 'li', function() {
		var url = $(this).data('url');
		$('#J_menu_list .J_menu_url[data-url="' + url + '"]').click();
		if ($(this).hasClass('first')) {
			$('#J_tag_list li').removeClass('active');
			$(this).addClass('active');
			$('iframe').removeClass('active');
			$('iframe:first').addClass('active');
		}
	})
	//关闭iframe
	$('#J_tag_list').on('click', '.remove-btn', function(e) {
		e.stopPropagation();
		$(this).parent('li').remove();
		var url = $(this).parent('li').data('url');
		var id = $('.J_menu_url[data-url="' + url + '"]').data('id');
		delete tagBox[id];
		$('iframe[data-url="' + url + '"]').remove();
		$('#J_tag_list li:last').click();
	})
})