$(function() {
	var nowDate = new Date();
    var nowTime = nowDate.getFullYear() + '-' + (nowDate.getMonth() + 1) + '-' + nowDate.getDate();
    $('input.J_search_time').val(nowTime);
	// add locale
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
    $('.J_date_btn').on('click', function() {
    	$(this).siblings('input').focus()
    })

	//获取排行榜数据
	var tpl = $('#J_tpl_rank').html();
	function getRank(day, type) {
		requestUrl('http://115.29.196.61/api/get_data_two', 'GET', {day: day}, function(data) {
			if (type == 0) {
				var html = juicer(tpl, data.count_buy);
				$('#J_count_rank').html(html);
			}
			if (type == 1) {
				var html = juicer(tpl, data.sum_buy);
				$('#J_sum_rank').html(html);
			}
			if (type == 2) {
				var html = juicer(tpl, data.count_cancel);
				$('#J_count_cancel').html(html);
			}
			if (type == 3) {
				var html = juicer(tpl, data.sum_cancel);
				$('#J_sum_cancel').html(html);
			}
		})
	}
	getRank(nowTime, 0);
	getRank(nowTime, 1);
	getRank(nowTime, 2);
	getRank(nowTime, 3);
	$('#J_search_btn').on('click', function() {
		getRank($('.J_search_time').val(), 0);
		getRank($('.J_search_time').val(), 1);
		getRank($('.J_search_time').val(), 2);
		getRank($('.J_search_time').val(), 3);
	})
})