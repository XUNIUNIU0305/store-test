$(function() {
	// 暂存全局数据
	g = {
		pay_tpl: $('#J_tpl_pay').html(),
		areaList: {}
	}

	Highcharts.setOptions({
		lang: {
			shortMonths: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']
		}
	})

	// 刷新下拉框
	function refresh($dom) {
		if ($dom) {
			$dom.selectpicker('refresh');
			$dom.selectpicker('show');
		} else {
			$('.selectpicker').selectpicker('refresh');
        	$('.selectpicker').selectpicker('show');
		}
	}
	// 获取用户级别
	function getUserLevel() {
		requestUrl('/data/home/user-level', 'GET', '', function(data) {
			var options = '';
			$.each(data, function(i, v) {
				options += '<option value="' + i + '">' + v + '</option>'
			})
			$('#J_user_level').html(options).find('option').attr('selected', 'selected');
			refresh($('#J_user_level'));
		})
	}
	getUserLevel()

	// 获取数据
	function getDateData(params) {
		addLoading($('#date-chart-dot-container'));
		addLoading($('#date-chart-bar-container'));
		$('#J_buy_subhead').html($('#J_buy_time').val());
		requestUrl('/data/buy/search', 'GET', params, function(data) {
			removeLoading($('#date-chart-dot-container'));
			removeLoading($('#date-chart-bar-container'));
			var items = data;

			// 生成Chart
			if (items.length < 1) return;
			var _data = [],
				_dataPay = [],
				_dataCancel = [],
				_start = new Date(items[0].date.date).getTime();
			var type = $('#J_type_list').val();
			if (type == 'hour') {
				var interval = 3600 * 1000;
				var format = {hour: '%H:%M'};
			}
			if (type == 'day') {
				var interval = 24 * 3600 * 1000;
				var format = {day: '%b月%e'};
			}
			if (type == 'week') {
				var interval = 7 * 24 * 3600 * 1000;
				var format = {week: '%b月%e'};
			}
			if (type == 'month') {
				var interval = 30 * 7 * 24 * 3600 * 1000;
				var format = {month: '%y-%b'};
			}
			$.each(items, function(i, val) {
				$.each(val.items, function(index, value) {
					var _date = new Date(value.date).getTime();
					_data.push([_date, value.total])
				})
				$.each(val.payedItems, function(index, value) {
					var _date = new Date(value.date).getTime();
					_dataPay.push([_date, value.total])
				})
				$.each(val.cancelItems, function(index, value) {
					var _date = new Date(value.date).getTime();
					_dataCancel.push([_date, value.total])
				})
			})
			$('#date-chart-dot-container').highcharts({
		        chart: {
		            type: 'scatter',
		            zoomType: 'xy'
		        },
		        title: {
		            text: '下单数据分析'
		        },
		        xAxis: {
		            type: 'datetime',
		            dateTimeLabelFormats: format
		        },
		        yAxis: {
		            title: {
		                text: '金额 (元)'
		            }
		        },
		        legend: {
		            layout: 'vertical',
		            align: 'left',
		            verticalAlign: 'top',
		            x: 100,
		            y: 70,
		            floating: true,
		            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
		            borderWidth: 1
		        },
		        credits: {
		            enabled: false
		        },
		        plotOptions: {
		            scatter: {
		                marker: {
		                    radius: 5,
		                    states: {
		                        hover: {
		                            enabled: true,
		                            lineColor: 'rgb(100,100,100)'
		                        }
		                    }
		                },
		                states: {
		                    hover: {
		                        marker: {
		                            enabled: false
		                        }
		                    }
		                },
		                tooltip: {
		                    headerFormat: '<b>{series.name}</b><br>',
		                    pointFormat: '{point.y} 元'
		                }
		            }
		        },
		        series: [{
		            name: '下单金额',
		            color: '#7cb5ec',
		            data: _data,
			        pointStart: _start,
	            	pointInterval: interval,
	            	pointIntervalUnit: type
	            },{
		            name: '订单付款',
		            color: '#f7a35c',
		            data: _dataPay,
			        pointStart: _start,
	            	pointInterval: interval,
	            	pointIntervalUnit: type
	            },{
		            name: '取消付款',
		            color: '#434348',
		            data: _dataCancel,
			        pointStart: _start,
	            	pointInterval: interval,
	            	pointIntervalUnit: type
	            }]
		    })
			// 柱状图
			var bar_x =  [],
				bar_total = [],
				bar_pay = [],
				bar_cancel = [];
			$.each(items, function(i ,v) {
				bar_x.push(v.date.key);
				bar_total.push(v.total);
				bar_pay.push(v.payedTotal);
				bar_cancel.push(v.cancelTotal);
			})
			$('#date-chart-bar-container').highcharts({
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: ''
		        },
		        xAxis: {
		            categories: bar_x,
		            crosshair: true
		        },
		        yAxis: {
		            min: 0,
		            title: {
		                text: '金额 (元)'
		            }
		        },
		        tooltip: {
		            valueSuffix: '元'
		        },
		        legend: {
			        enabled: true
			    },
		        credits: {
		            enabled: false
		        },
		        plotOptions: {
		            column: {
		                pointPadding: 0.2,
		                borderWidth: 0
		            }
		        },
		        series: [{
		            name: '下单金额',
		            data: bar_total
		        },{
		        	name: '订单付款金额',
		        	data: bar_pay
		        }, {
		        	name: '订单取消金额',
		        	data: bar_cancel
		        }]
		    });

		    // 生成表格
		    $('#J_pay_table').html(juicer(g.pay_tpl, items));
		})
	}

	// 下单搜索
	$('#J_search_buy').on('click', function() {
		var _date = $('#J_buy_time').val().split(' 至 ');
		var user_level = $('#J_user_level').val();
		var by = $('#J_type_list').val();
		var params = {
			start: _date[0],
			end: _date[1],
			user_level: user_level,
			by: by
		}
		if (user_level == null) {
			alert('请选择搜索对象！');
			return
		}
		getDateData(params)
	})

	//获取区域列表
	var levels = ['', '省', '辅导区', '督导区', '运营商', '小组'];
    function getArea(level, id) {
        requestUrl('/data/home/self-area', 'GET', {area_id: id }, function(data) {
            var html = '<div class="col-xs-2">\
            				<label for="">&nbsp;</label>\
                            <select class="selectpicker J_area_box" data-width="100%" data-haschild="' + data.hasChild + '" data-level="' + level + '">\
                                <option value="-1">请选择' + levels[level] + '</option>';
            for (var i = 0; i < data.items.length; i++) {
                html += '<option value="' + data.items[i].id + '">' + data.items[i].name + '</option>'
            }
            html += '</select>\
                </div>';
            $('#J_select_box').append(html);
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

    //追加区域 
    $('#J_add_area').on('click', function() {
    	if (Object.keys(g.areaList).length > 9) {
    		alert("最多查询10个条件！")
    		return
    	};
        var len = $('#J_select_box select.J_area_box').length;
        var val = $('select.J_area_box').eq(len - 1).val();
        var level = $('select.J_area_box').eq(len - 1).data('level');
        if (val == -1) {
            val = $('select.J_area_box').eq(len - 2).val();
            level = $('select.J_area_box').eq(len - 2).data('level');
        }
        if (val == -1) return;
        var name = $('select.J_area_box[data-level="' + level + '"] option:selected').html();
        var typeId = $('#J_buy_type').val();
        var type = $('#J_buy_type option:selected').html();
        var html = '<div class="tag-label" data-id="' + val + ',' + typeId + '">' + name + ' | ' + type + ' <i class="close">×</i></div>'
        if (g.areaList[val + ',' + typeId]) {
            return
        }
        g.areaList[val + ',' + typeId] = {
        	area: val,
        	type: typeId
        }
        $('#J_search_box').append(html);
    })
    $('#J_search_box').on('click', '.close', function() {
    	var id = $(this).parent('.tag-label').data('id');
        delete g.areaList[id];
        $(this).parent('.tag-label').remove();
    })

    // 获取区域查询数据
    function getAreaData(params) {
    	$('#J_area_subhead').html($('#J_area_time').val());
    	addLoading($('#region-chart-dot-container'));
    	addLoading($('#region-chart-bar-container'));
    	requestUrl('/data/buy/area-search', 'GET', params, function(data) {
    		removeLoading($('#region-chart-dot-container'));
    		removeLoading($('#region-chart-bar-container'));
    		var items = data.items;
    		// 生成Chart
			if (items.length < 1) return;
			var _data = [],
				_start = new Date($('#J_area_time').val().split(' 至 ')[0]).getTime();
			var type = $('#J_area_type_list').val();
			if (type == 'hour') {
				var interval = 3600 * 1000;
				var format = {hour: '%H:%M'};
			}
			if (type == 'day') {
				var interval = 24 * 3600 * 1000;
				var format = {day: '%b月%e'};
			}
			if (type == 'week') {
				var interval = 7 * 24 * 3600 * 1000;
				var format = {week: '%b月%e'};
			}
			if (type == 'month') {
				var interval = 30 * 7 * 24 * 3600 * 1000;
				var format = {month: '%y-%b'};
			}
			$.each(items, function(i, val) {
				var _obj = {
					name: val.query,
					pointStart: _start,
	            	pointInterval: interval,
	            	pointIntervalUnit: type
				};
				var _tdata = []
				$.each(val.list, function(index, value) {
					var _date = new Date(value.date.date).getTime();
					_tdata.push([_date, value.total])
				})
				_obj['data'] = _tdata;
				_data.push(_obj);
			})
			$('#region-chart-dot-container').highcharts({
		        chart: {
		            type: 'scatter',
		            zoomType: 'xy'
		        },
		        title: {
		            text: '下单数据分析'
		        },
		        xAxis: {
		            type: 'datetime',
		            dateTimeLabelFormats: format
		        },
		        yAxis: {
		            title: {
		                text: '金额 (元)'
		            }
		        },
		        legend: {
		            layout: 'vertical',
		            align: 'left',
		            verticalAlign: 'top',
		            x: 100,
		            y: 70,
		            floating: true,
		            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
		            borderWidth: 1
		        },
		        credits: {
		            enabled: false
		        },
		        plotOptions: {
		            scatter: {
		                marker: {
		                    radius: 5,
		                    states: {
		                        hover: {
		                            enabled: true,
		                            lineColor: 'rgb(100,100,100)'
		                        }
		                    }
		                },
		                states: {
		                    hover: {
		                        marker: {
		                            enabled: false
		                        }
		                    }
		                },
		                tooltip: {
		                    headerFormat: '<b>{series.name}</b><br>',
		                    pointFormat: '{point.y} 元'
		                }
		            }
		        },
		        series: _data
		    })
		    // 生成柱状图
		    var bar_x = [],
				bar_data= [];
			$.each(items[0].list, function(i, v) {
				bar_x.push(v.date.key);
			})
			$.each(items, function(index, val) {
				var _obj = {
					name: val.query
				};
				var _tdata = [];
				$.each(val.list, function(i, v) {
					_tdata.push(items[index].list[i].total)	
				})
				_obj['data'] = _tdata;
				bar_data.push(_obj)
			})

		    $('#region-chart-bar-container').highcharts({
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '下单数据分析'
		        },
		        xAxis: {
		            categories: bar_x,
		            crosshair: true
		        },
		        yAxis: {
		            min: 0,
		            title: {
		                text: '销售额（元）'
		            }
		        },
		        tooltip: {
		            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
		            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
		            '<td style="padding:0"><b>{point.y:.1f} 元</b></td></tr>',
		            footerFormat: '</table>',
		            shared: true,
		            useHTML: true
		        },
		        plotOptions: {
		            column: {
		                pointPadding: 0.2,
		                borderWidth: 0
		            }
		        },
		        series: bar_data
		    })

		    // 生成表格
		    var times = '<tr><td>时间</td></tr>';
	    	$.each(items[0].list, function(index, val) {
	    		times += '<tr><td>' + val.date.key + '</td></tr>'
	    	})
		    times += '</tr>'
		    $('#J_area_table_time').html(times);
		    var name = '<tr>';
		    $.each(items, function(i, v) {
		    	name += '<td>' + v.query + '</td>'
		    })
		    name += '</tr>';
		    var price = '';
	    	$.each(items[0].list, function(index, val) {
	    		price += '<tr>'
	    		$.each(items, function(id, value) {
	    			price += '<td>' + items[id].list[index].total + '</td>'
	    		})
	    		price +='</tr>'
	    	})

		    $('#J_area_table_detail').html(name + price);
    	})
    }

    // 按区域搜索
    $('#J_area_btn').on('click', function() {
    	var _date = $('#J_area_time').val().split(' 至 ');
		var by = $('#J_area_type_list').val();
		var query = [];
		$.each(g.areaList, function(i, val) {
			query.push(val)
		})
		var params = {
			start: _date[0],
			end: _date[1],
			query: query,
			by: by,
		}
		getAreaData(params)
    })




})