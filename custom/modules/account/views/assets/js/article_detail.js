$(function() {
	var isNew = true;
	function getArticleDetail(id) {
		requestUrl('/account/article/get-detail', 'GET', {id: id}, function(data) {
			if (data.footer != null) {
				isNew = false
			}
			$('.article-title').html(data.title);
			$('#J_article_title').html(data.title);
			$('#J_article_content').html(data.content);
			$('#J_article_footer').html(data.footer);
			editor.html(data.footer);
			$('#J_article_img').attr('src', '/account/article/qrcode?id=' + url('?id') + '&footer_id=' +  data.footer_id);
		}, function(data) {
			alert(data.data.errMsg);
			window.location.href = '/account/article/index';
		})
	}
	getArticleDetail(url('?id'));
	//编辑页脚
	$('#J_edit_footer').on('click', function() {
		$('#J_edit_box').removeClass('hidden');
		$('#J_article_img').addClass('hidden');
	})
	$('#J_edit_cancel').on('click', function() {
		$('#J_edit_box').addClass('hidden');
		$('#J_article_img').removeClass('hidden');
	})
	$('#J_create_footer').on('click', function() {
		var html = editor.html();
		if (html == '') {
			alert('内容不能为空！');
			return;
		}
		//是否第一次创建页脚
		if (isNew) {
			requestUrl('/account/article/create', 'POST', {footer_content: html}, function(data) {
				window.location.reload();
			})
		} else {
			requestUrl('/account/article/edit', 'POST', {footer_content: html}, function(data) {
				window.location.reload();
			})
		}
	})
})