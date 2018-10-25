$(function() {
	//获取文章列表
	var tpl = $('#J_tpl_list').html();
	var compile_tpl = juicer(tpl);
	function getArticleList(page, size) {
		requestUrl('/account/article/list', 'GET', {current_page: page, page_size: size}, function(data) {
			if (data.codes.length == 0) {
				return
			}
			var html = compile_tpl.render(data);
			$('#J_article_list').html(html);
			pagingBuilder.build($('#J_article_page'), page, size, data.total_count);
			pagingBuilder.click($('#J_article_page'), function(page) {
				getArticleList(page, size);
			});
		})
	}
	getArticleList(1, 20)
})