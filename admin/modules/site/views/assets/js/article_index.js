$(function() {
	//获取文章列表
	var tpl = $('#J_tpl_list').html();
	var compile_tpl = juicer(tpl);
	function getArticleList(page, size) {
		requestUrl('/site/article/list', 'GET', {current_page: page, page_size: size}, function(data) {
			var html = compile_tpl.render(data);
			$('#J_article_list').html(html);
			$('#J_wechat_content').html('');
			pagingBuilder.build($('#J_article_page'), page, size, data.total_count);
			pagingBuilder.click($('#J_article_page'), function(page) {
				getArticleList(page, size);
			});
		})
	}
	getArticleList(1, 20)
	$('#J_article_list').on('click', 'li', function() {
		$(this).addClass('active').siblings('li').removeClass('active');
		var id = $(this).data('id');
		requestUrl('/site/article/content', 'GET', {id: id}, function(data) {
			$('#J_wechat_content').html(data.data)
		})
	})
	//删除文章
	$('#apxModalAdminDel').on('show.bs.modal', function(e) {
		var $this = $(e.relatedTarget);
		var id = $this.data('id');
		$('#J_del_article').off().on('click', function() {
			$('#apxModalAdminDel').modal('hide');
			requestUrl('/site/article/remove', 'POST', {id: id}, function(data) {
				$('#J_alert_content').html('删除成功！');
				$('#apxModalAdminAlert').modal('show');
				var page = $('#J_article_page li[class*="active"]').data('page');
				getArticleList(page, 20);
			})
		})
	})
})