$(function() {
	// 获取提现列表
	var tpl = $('#J_tpl_list').html();

	$.fn.datepicker.dates["zh-CN"] = {
        days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
        daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
        daysMin: ["日", "一", "二", "三", "四", "五", "六"],
        months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        monthsShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
        today: "今日",
        clear: "清除",
        format: "yyyy年mm月dd日",
        titleFormat: "yyyy年mm月",
        weekStart: 1
    }
    // init the datepicker
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-CN'
    });

    var nowDate = new Date()
    var nowYear = nowDate.getFullYear()
    var nowMonth = nowDate.getMonth() + 1
    var nowDay = nowDate.getDate()

    function dateFormat(val){
    	if(val < 10){
    		return '0' + val
    	}else{
    		return val
    	}
    }
    
	function getList(params) {
		var _data
		_data = {
			current_page: params.current_page,
			page_size: params.page_size
		}
		$.extend(_data, params);
		requestUrl('/account/statement/list', 'GET', _data, function(data) {
			$('#J_steam_list').html(juicer(tpl, data.statements));
			pagingBuilder.build($('#J_page_list'), _data.current_page, _data.page_size, data.total_count);
			pagingBuilder.click($('#J_page_list'), function(page) {
				getList({page_size: 20,current_page: page})
			})
		})
	}
	getList({
		current_page: 1,
		page_size: 20
	})

	function searchList(params) {
		var _data
		_data = {
			current_page: params.current_page,
			page_size: params.page_size,
			search: {'time': [$('.J_search_timeStart').val(), $('.J_search_timeEnd').val()]}
		}
		$.extend(_data, params);
		requestUrl('/account/statement/list', 'GET', _data, function(data) {
			$('#J_steam_list').html(juicer(tpl, data.statements));
			pagingBuilder.build($('#J_page_list'), _data.current_page, _data.page_size, data.total_count);
			pagingBuilder.click($('#J_page_list'), function(page) {
				searchList({page_size: 20,current_page: page,search: _data.search})
			})
		})
	}

	$('#search').click(function(){
		if($('.J_search_timeStart').val() == '' || $('.J_search_timeEnd').val() == ''){
			getList({
				current_page: 1,
				page_size: 20
			})
		}else{
			searchList({
				current_page: 1,
				page_size: 20,
				search: {'time': [$('.J_search_timeStart').val(), $('.J_search_timeEnd').val()]}
			})
		}
		
	})
})