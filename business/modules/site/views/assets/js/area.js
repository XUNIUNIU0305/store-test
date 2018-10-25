$(function() {
	var nowDate = new Date(),
    _Month = (nowDate.getMonth() + 1);
    _day = nowDate.getDate();
    if (_Month < 10) {
        _Month = '0' + _Month
    }
    if (_day < 10) {
        _day = '0' + _day
    }
    var nowTime = nowDate.getFullYear() + '-' + _Month + '-' + _day;
    $('input.J_search_timeStart').val(nowTime);
    $('input.J_search_timeEnd').val(nowTime);
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
        language: 'zh-CN',
        orientation: 'bottom'
    });
    $('.J_timeStart_show').on('click', function() {
        $(this).parents('.tab-pane').find('.J_search_timeStart').datepicker('show')
    })
    $('.J_timeEnd_show').on('click', function() {
        $(this).parents('.tab-pane').find('.J_search_timeEnd').datepicker('show')
    })

    var _page = 1,
    	_size = 20;

	//获取全国业绩
	var tpl_table = juicer($('#J_tpl_data').html());
	function getChart(page, size, level, start, end, field, type) {
		var data = {
			current_page: page,
			page_size: size,
			level: level,
			date_from: start,
			date_to: end,
			sort_field: field,
			sort_type: type
		}
		requestUrl('/site/area/chart', 'GET', data, function(data) {
			var html = tpl_table.render(data);
			$('#J_area_' + level).find('.J_table_data').html(html);
			//分页
			pagingBuilder.build($('.J_data_page_' + level), page, size, data.total_count);
			pagingBuilder.click($('.J_data_page_' + level), function(page) {
				getChart(page, _size, level, start, end, field, type);
			});
			//柱状图
			var _area = [0],
                _normal = [0],
                _refund = [0],
                _reject = [0];
            $.each(data.list, function(i, val) {
                _area.push(val.name);
                _normal.push(val.normal.toFixed(2));
                _refund.push(val.refund.toFixed(2));
                _reject.push(val.reject.toFixed(2));
            })
            var barChart = echarts.init(document.getElementById('J_chart_' + level));
            var optionBigBar = {
                grid: {
                    left: '5%',
                    right: '5%'
                },
                tooltip: {
                    trigger: 'axis',
                    position: function (pt) {
                        return [pt[0], '10%'];
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: _area
                },
                yAxis: {
                    type: 'value',
                    boundaryGap: [0, '100%']
                },
                dataZoom: [{
	                type: 'inside',
	                start: 0,
	                end: 30
	            }, {
	                start: 0,
	                end: 30,
	                handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
	                handleSize: '80%',
	                handleStyle: {
	                    color: '#fff',
	                    shadowBlur: 3,
	                    shadowColor: 'rgba(0, 0, 0, 0.6)',
	                    shadowOffsetX: 2,
	                    shadowOffsetY: 2
	                }
	            }],
                series: [{
                    name: ['正常业绩金额'],
                    type: 'bar',
                    silent: true,
                    data: _normal
                },
                {
                    name: ['退换货金额'],
                    type: 'bar',
                    silent: true,
                    data: _refund
                },
                {
                    name: ['驳回金额'],
                    type: 'bar',
                    silent: true,
                    data: _reject
                }]
            };
            barChart.setOption(optionBigBar)
		})
	}
	//搜索
	$('.J_search_data').on('click', function() {
		var level = $(this).parents('.tab-pane').data('level');
		var start = $(this).siblings('.input-group').find('input.J_search_timeStart').val();
		var end = $(this).siblings('.input-group').find('input.J_search_timeEnd').val();
		getChart(_page, _size, level, start, end);
	})
    //排序
    $('.J_sort_icon').on('click', function() {
        var rank = $(this).data('flag');
        var type = $(this).data('type');
        var level = $(this).parents('.tab-pane').data('level');
        var start = $(this).parents('.tab-pane').find('input.J_search_timeStart').val();
        var end = $(this).parents('.tab-pane').find('input.J_search_timeEnd').val();
        $(this).parents('tr').find('.J_sort_icon').removeClass('active');
        $(this).parents('tr').find('.J_sort_icon').removeClass('top');
        $(this).addClass('active');
        var sort = 0;
        if (rank) {
            $(this).addClass('top');
            sort = 0;
        } else {
            sort = 1;
        }
        $(this).data('flag', !rank);
        getChart(_page, _size, level, start, end, type, sort);
    })
})