$(function() {

	// 暂存数据
	g = {
		first: [],
		second: [],
		three: [],
		payed: [],
		finish: []
	}

	// 刷新下拉框
	function refresh() {
		$('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('show');
	}

	// 获取数据
	function getData(params) {
		addLoading($('#data_loading_box'));
		requestUrl('/data/conversion/list', 'GET', params, function(data) {
			removeLoading($('#data_loading_box'));
			$('#J_first_num').html(data.first.num);
			$('#J_payed_num').html(data.payed.num);
			$('#J_finish_num').html(data.finish.num);
			$('#J_second_num').html(data.second.num);
			$('#J_three_num').html(data.three.num);
			$('#J_first_con').html(data.first.rate);
			$('#J_payed_con').html(data.payed.rate);
			$('#J_finish_con').html(data.finish.rate);
			$('#J_second_con').html(data.second.rate);
			$('#J_three_con').html(data.three.rate);
			g.first = data.first.items;
			g.second = data.second.items;
			g.three = data.three.items;
			g.payed = data.payed.items;
			g.finish = data.finish.items;
			showData('first');
		})
	}
	getData()
	function showData(type) {
		var items = g[type];
		var xtitle = [],
			total = [],
			rage = [];
		$.each(items, function(i, v) {
			xtitle.push(v.date.key);
			total.push(v.total);
			rage.push(v.rage);
		})
		$('#H_chart_box').highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: '人数及转化率'
	        },
	        subtitle: {
	            text: '数据来源: 9daye power'
	        },
	        xAxis: [{
		            categories: xtitle,
		            crosshair: true
	        }],
	        yAxis: [{ // Primary yAxis
	            labels: {
	                format: '{value} %',
	                style: {
	                    color: Highcharts.getOptions().colors[1]
	                }
	            },
	            title: {
	                text: '转化率',
	                style: {
	                    color: Highcharts.getOptions().colors[1]
	                }
	            }
	        }, { // Secondary yAxis
	            title: {
	                text: '销售金额（元）',
	                style: {
	                    color: Highcharts.getOptions().colors[0]
	                }
	            },
	            labels: {
	                format: '{value} 元',
	                style: {
	                    color: Highcharts.getOptions().colors[0]
	                }
	            },
	            opposite: true
	        }],
	        tooltip: {
	            shared: true
	        },
	        credits: {
	            enabled: false
	        },
	        legend: {
	            layout: 'horizontal',
	            align: 'center',
	            verticalAlign: 'bottom',
	            floating: false,
	            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
	        },
	        series: [{
	            name: '销售额',
	            type: 'column',
	            yAxis: 1,
	            data: total,
	            tooltip: {
	                valueSuffix: ' 元'
	            }
	        }, {
	            name: '转换率',
	            type: 'spline',
	            data: rage,
	            tooltip: {
	                valueSuffix: ' %'
	            }
	        }]
	    });
	}
	
	//获取区域列表
	var levels = ['', '省', '辅导区', '督导区', '运营商', '小组'];
    function getArea(level, id) {
        requestUrl('/data/home/self-area', 'GET', {area_id: id }, function(data) {
            var html = '<div class="col-xs-2">\
            				<label for="">&nbsp;</label>\
                            <select class="selectpicker J_area_box btn-group-xs" data-width="100%" data-haschild="' + data.hasChild + '" data-level="' + level + '">\
                                <option value="-1">请选择' + levels[level] + '</option>';
            for (var i = 0; i < data.items.length; i++) {
                html += '<option value="' + data.items[i].id + '">' + data.items[i].name + '</option>'
            }
            if (level === 1) {
            	$('#J_area_province').html(html);
            } else {
	            html += '</select>\
	                </div>';
	            $('#J_select_box').append(html);
            }
            refresh()
        })
    }
    getArea(1, 0);
    //区域联动
    $('#J_select_box').on('change', 'select.J_area_box', function() {
        if (!$(this).data('haschild'))return;
        var level = $(this).data('level') - 0;
        var val = $(this).val() - 0;
        if (val === -1) {
            return };
        $('select.J_area_box:gt(' + (level - 1) + ')').parents('.col-xs-2').remove();
        refresh();
        getArea(level + 1, val);
    })

    // 搜索
    $('#J_search_btn').on('click', function() {
    	var len = $('#J_select_box select.J_area_box').length;
        var val = $('select.J_area_box').eq(len - 1).val();
        var level = $('select.J_area_box').eq(len - 1).data('level');
        if (val == -1) {
            val = $('select.J_area_box').eq(len - 2).val();
            level = $('select.J_area_box').eq(len - 2).data('level');
        }
        if (val == -1) {
        	alert('请选择区域！');
        	return
        }
        var user_level = $('#J_user_list').val();
        if (user_level == null) {
        	alert('请选择搜索对象！')
        	return
        }
        var by = $('#J_type_list').val();
        var date = $('#J_time_box').val().split(' 至 ');
        var params = {
        	start: date[0],
        	end: date[1],
        	user_level: user_level,
        	area_id: val,
        	by: by
        }
        getData(params)
    })

    // 切换数据
    $('#J_items_box .item').on('click', function() {
    	$(this).addClass('active').siblings().removeClass('active');
    	var type = $(this).data('type');
    	showData(type);
    })
    
})