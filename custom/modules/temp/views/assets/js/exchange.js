$(function() {
	var _page = 1,
		_size = 9999;
	var compiled_list = juicer($('#J_tpl_list').html());
	//获取领水券列表
	function getExchangeList(page, size) {
		var _data = {
			current_page: page,
			page_size: size
		}
		requestUrl('/temp/exchange/list', 'GET', _data, function(data) {
			var html = compiled_list.render(data);
			$('#J_exchange_list').html(html);
		})
	}
	getExchangeList(_page, _size);
})