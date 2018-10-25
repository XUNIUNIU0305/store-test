$(function () {
    // 初始化时间选择
    $('.real-time-input').daterangepicker({
        "showDropdowns": true,
        "autoApply": true,
        "singleDatePicker": true,
        "locale": {
            "direction": "ltr",
            "format": "YYYY-MM-DD",
            "separator": " 至 ",
            "applyLabel": "确定",
            "cancelLabel": "取消",
            "fromLabel": "From",
            "toLabel": "To",
            "daysOfWeek": [
                "周日",
                "周一",
                "周二",
                "周三",
                "周四",
                "周五",
                "周六"
            ],
            "monthNames": [
                "一月",
                "二月",
                "三月",
                "四月",
                "五月",
                "六月",
                "七月",
                "八月",
                "九月",
                "十月",
                "十一月",
                "十二月"
            ],
            "firstDay": 1
        },
        "startDate": new Date(),
        "opens": "left",
        "drops": "up"
    });
	// 数据总览
	function getTotalData() {
		addLoading($('.J_total_loading_box'));
		requestUrl('/data/real-time/total-preview', 'GET', '', function(data) {
			removeLoading($('.J_total_loading_box'));
			$('#J_total_num').html(data.totalNum);
			$('.J_custom_num').html(data.customNum);
			$('.J_day_fee').html(data.dayFeeNum);
			$('#J_total_conversion').html(data.totalConversionRate + '%');
			$('#J_day_activity').html(data.dayActivity + '%');

			$('#J_day_total_fee').text(data.dayTotalFee.toFixed(2));
			$('#J_day_order').text(data.dayOrderNum);

			$('#J_code_num').text(data.codeNum);
			$('#J_register_num').text(data.registerNum);
			$('#J_unit_price').text(data.unitPrice);
			$('#J_unit_num').text(data.unitNum);

            // 图表
            if (!Highcharts.theme) {
                Highcharts.setOptions({
                    chart: {
                        backgroundColor: '#fff'
                    },
                    colors: ['#42a5f5', '#f19149'],
                    credits: {
                        enabled: false
                    },
                    title: ''
                });
            }
            Highcharts.chart('container', {
                chart: {
                    type: 'solidgauge',
                    marginTop: 20
                },
                tooltip: {
                    borderWidth: 0,
                    backgroundColor: 'none',
                    shadow: false,
                    style: {
                        fontSize: '16px'
                    },
                    pointFormat: '{series.name}<br><span style="font-size:2em; color: {point.color}; font-weight: bold">{point.y}%</span>',
                    positioner: function (labelWidth, labelHeight, point) {
                        return {
                            x: 50,
                            y: 75
                        };
                    }
                },
                pane: {
                    startAngle: 0,
                    endAngle: 360,
                    background: [{ // Track for Move
                        outerRadius: '110%',
                        innerRadius: '88%',
                        backgroundColor: Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0.3).get(),
                        borderWidth: 0
                    }, { // Track for Exercise
                        outerRadius: '87%',
                        innerRadius: '63%',
                        backgroundColor: Highcharts.Color(Highcharts.getOptions().colors[1]).setOpacity(0.3).get(),
                        borderWidth: 0
                    }]
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    lineWidth: 0,
                    tickPositions: []
                },
                plotOptions: {
                    solidgauge: {
                        borderWidth: '14px',
                        dataLabels: {
                            enabled: false
                        },
                        linecap: 'round',
                        stickyTracking: false
                    }
                },
                series: [{
                    name: '总转化率',
                    borderColor: Highcharts.getOptions().colors[0],
                    data: [{
                        color: Highcharts.getOptions().colors[0],
                        radius: '100%',
                        innerRadius: '100%',
                        y: data.totalConversionRate
                    }]
                }, {
                    name: '日活跃度',
                    borderColor: Highcharts.getOptions().colors[1],
                    data: [{
                        color: Highcharts.getOptions().colors[1],
                        radius: '75%',
                        innerRadius: '75%',
                        y: data.dayActivity
                    }]
                }]
            });
		})
	}
	getTotalData()

	// 热销商品
    var hot_tpl = $('#J_tpl_hot').html();
    function toFixed(data) {
        return data.toFixed(2)
    }
    juicer.register('toFixed', toFixed)
	function getHotPro(num) {
		requestUrl('/data/day/custom-consumption', 'GET', {show: num}, function(data) {
			$('#J_hot_list').html(juicer(hot_tpl, data))
		})
	}
    getHotPro(12)
    $('select.J_show_select').on('change', function() {
        var i = $(this).val();
        getHotPro(i)
    })
	
	// 门店开单情况
	function proportion(data) {
        if (data.daily_custom_consumption_count + data.daily_custom_unconsumption_count === 0) {
            return '0.00%'
        }
		var x = (data.daily_custom_consumption_count / (data.daily_custom_consumption_count + data.daily_custom_unconsumption_count) * 100).toFixed(2);
		return x + '%'
	}
	juicer.register('proportion', proportion);
	var store_tpl = $('#J_store_tpl').html();
	function getStoreSale(_data) {
		requestUrl('/data/day/area-consumption', 'GET', _data, function(data) {
            $('#J_store_list').html(juicer(store_tpl, data.areas_consumption.data))
            var area_name = data.parent_area_info.area_name;
            var area_id = data.parent_area_info.area_id;
            var area_level = data.parent_area_info.area_level;
            countLevel(area_level)
            $('#J_area_box').append('<a href="javascript:;" class="J_area_level disabled" data-id="' + area_id + '" data-level="' + area_level + '">' + area_name + '></a>')
            pagingBuilder.build($('#J_page_box'), _data.current_page, _data.page_size, data.areas_consumption.total_count)
            pagingBuilder.click($('#J_page_box'), function(page) {
                _data.current_page = page
                getStoreSale(_data)
            })
		})
	}
	getStoreSale({
		current_page: 1,
		page_size: 10
    })
    
    // 等级导航切换
    $('#J_area_box').on('click', '.J_area_level', function() {
        var id = $(this).data('id')
        getStoreSale({
            current_page: 1,
            page_size: 10,
            parent_area_id: id
        })
    })

    $('#J_store_list').on('click', '.J_area_name', function() {
        var id = $(this).data('id')
        getStoreSale({
            current_page: 1,
            page_size: 10,
            parent_area_id: id
        })
    })

    // 判断开单情况当前level是否能点击
    function countLevel(level) {
        $.each($('#J_area_box a'), function() {
            if ($(this).data('level') >= level) {
                $(this).remove()
            }
            if ($(this).data('level') === level) {
                $(this).addClass('disabled')
            } else {
                $(this).removeClass('disabled')
            }
        })
    }

    // 判断注册情况当前level是否能点击
    function countRegisterLevel(level) {
        $.each($('#J_area_register_box a'), function() {
            if ($(this).data('level') >= level) {
                $(this).remove()
            }
            if ($(this).data('level') === level) {
                $(this).addClass('disabled')
            } else {
                $(this).removeClass('disabled')
            }
        })
    }

    // 获取注册门店数
    var tpl_register = $('#J_tpl_register').html()
    var _currentArea;
    function getRegister(_data) {
        var date = $('#J_register_time').val();
        var pram = {
            date: date
        }
        $.extend(pram, _data)
        requestUrl('/data/day/day-register', 'GET', pram, function(data) {
            $('#J_register_list').html(juicer(tpl_register, data.present_area_info.data))
            var area_name = data.parent_area_info.area_name;
            var area_id = data.parent_area_info.area_id;
            var area_level = data.parent_area_info.area_level;
            _currentArea = data.parent_area_info.area_id;
            countRegisterLevel(area_level)
            $('#J_area_register_box').append('<a href="javascript:;" class="J_area_level disabled" data-id="' + area_id + '" data-level="' + area_level + '">' + area_name + '></a>')
        })
    }
    getRegister()
    // 等级导航切换
    $('#J_area_register_box').on('click', '.J_area_level', function() {
        var id = $(this).data('id')
        getRegister({
            parent_area_id: id
        })
    })
    $('#J_register_list').on('click', '.J_area_name', function() {
        var id = $(this).data('id')
        getRegister({
            parent_area_id: id
        })
    })
    $('#J_register_time').on('change', function() {
        getRegister({
            parent_area_id: _currentArea
        })
    })

    // 消费及未消费门店列表查询
    var modal_store_tpl = $('#J_modal_store_tpl').html();
    function quetyStore(params) {
        var _default = {
            current_page: 1,
            page_size: 10
        }
        $.extend(_default, params)
        requestUrl('/data/consumed-custom/get-info', 'GET', _default, function(data) {
            $('#J_modal_store_list').html(juicer(modal_store_tpl, data.codes))
            if (data.codes.length < 1) {
                $('#J_modal_page_box').html('')
            } else {
                pagingBuilder.build($('#J_modal_page_box'), _default.current_page, _default.page_size, data.total_count);
                pagingBuilder.click($('#J_modal_page_box'), function(page) {
                    _default.current_page = page
                    quetyStore(_default)
                });
            }
            $('#modalStoreExpense').modal('show')
        })
    }
    $('#J_store_list').on('click', '.expense-store', function() {
        $('#J_modal_store_list').html('')
        var id = $(this).data('id');
        var type = $(this).data('type');
        var name = $(this).data('name');
        if (type == 0) {
            var t_name = '未消费'
        } else {
            var t_name = '已消费'
        }
        $('#modalStoreExpense .modal-title').text(name + t_name + '门店列表')
        quetyStore({
            area_id: id,
            is_consumed: type
        })
    })

});
