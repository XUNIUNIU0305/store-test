$(function() {
	var _page = 1,
 		_size = 20,
        tpl = $('#J_tpl_list').html();
 	//获取已使用列表
 	function getExchangeList(page, size) {
 		var _data = {
 			current_page: page,
 			page_size: size
 		}
 		requestUrl('/temp/list/list', 'GET', _data, function(data) {
 			var html = juicer(tpl, data);
 			$('#J_exchange_list').html(html);
 			pagingBuilder.build($('#J_exchange_page'), page, size, data.total_count);
 			pagingBuilder.click($('#J_exchange_page'), function(page) {
 				getExchangeList(page, size);
 			});
 		})
 	}
 	getExchangeList(_page, _size);	
})
