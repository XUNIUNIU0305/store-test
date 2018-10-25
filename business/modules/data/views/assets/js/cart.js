$(function() {
	var g = {
		areaLevel: {},
		areaList: {},
		levelData: {},
		areaData: {}
	}
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

	// 切换搜索类型 各级或精准
	$('#J_tabs_list a').on('click', function() {
		$(this).addClass('active').siblings().removeClass('active');
		var type = $(this).data('type');
		if (type === 'area') {
			$('.J_search_area').removeClass('hidden');
			$('.J_search_detail').addClass('hidden');
			$('#J_level_table').removeClass('hidden');
			$('#J_detail_table').addClass('hidden');
		}
		if (type === 'detail') {
			$('.J_search_area').addClass('hidden');
			$('.J_search_detail').removeClass('hidden');
			$('#J_detail_table').removeClass('hidden');
			$('#J_level_table').addClass('hidden');
		}
	})

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

    //追加区域 
    $('#J_add_area').on('click', function() {
        var len = $('#J_select_box select.J_area_box').length;
        var val = $('select.J_area_box').eq(len - 1).val();
        var level = $('select.J_area_box').eq(len - 1).data('level');
        if (val == -1) {
            val = $('select.J_area_box').eq(len - 2).val();
            level = $('select.J_area_box').eq(len - 2).data('level');
        }
        if (val == -1) return;
        var name = $('select.J_area_box[data-level="' + level + '"] option:selected').html();
        var html = '<span class="btn btn-item" data-id="' + val + '">' + name + '</span>';
        if (g.areaList[val]) {
            return
        }
        g.areaList[val] = val;
        $('#J_search_box').append(html);
    })
    $('#J_search_box').on('click', '.btn-item', function() {
    	var id = $(this).data('id');
        delete g.areaList[id];
        $(this).remove();
    })

	// 获取区域级别
	function getAreaLevel() {
		requestUrl('/data/home/level', 'GET', '', function(data) {
			g.areaLevel = data;
			var options = '';
			$.each(data, function(i, v) {
				if (i !== '0') {
					options += '<option value="' + i + '">' + v + '</option>'
				}
			})
			$('#J_area_level').append(options);
			refresh($('#J_area_level'));
		})
	}
	getAreaLevel()

	// 获取用户级别
	function getUserLevel() {
		requestUrl('/data/home/user-level', 'GET', '', function(data) {
			var options = '';
			$.each(data, function(i, v) {
				options += '<option value="' + i + '">' + v + '</option>'
			})
			$('#J_user_level').html(options).find('option').eq(0).attr('selected', 'selected');
			$('#J_user_level2').html(options).find('option').eq(0).attr('selected', 'selected');
			refresh($('#J_user_level'));
			refresh($('#J_user_level2'));
		})
	}
	getUserLevel()

	// 获取各级购物车数据
	function searchLevelData(param) {
		$('#J_time_title').html($('#J_level_time').val());
		addLoading($('#H_chart_box'));
		requestUrl('/data/cart/search', 'GET', param, function(data) {
			removeLoading($('#H_chart_box'));
			g.levelData = data;
			var type = $('#J_level_list span[class*="active"]').data('type');
			showLevelData(type);


			// 生成表格
			var table = '',
				_len = Math.ceil(data.items.length/2);
			var table_title = '<table>\
							<tbody>\
								<tr>\
		                            <td>序号</td>\
		                            <td>省份</td>\
		                            <td>购物车金额(元)</td>\
		                        </tr>';
			var arr1 = data.items.slice(0, _len + 1);
			var arr2 = data.items.slice(_len);
			table += table_title;
			$.each(arr1, function(i, v) {
				table += '<tr>\
							<td>' + (i - 0 + 1) + '</td>\
							<td>' + v.name + '</td>\
							<td>' + v.total + '</td>\
						</tr>'
			})
			table += '</tbody>\
				</table>';
			table += table_title;
			$.each(arr2, function(i, v) {
				table += '<tr>\
							<td>' + (i - 0 + _len + 2) + '</td>\
							<td>' + v.name + '</td>\
							<td>' + v.total + '</td>\
						</tr>'
			})
			table += '</tbody>\
				</table>';
			$('#J_level_table').html(table);
		})
	}
	searchLevelData()
	function showLevelData(type) {
		var data = g.levelData;
		var xtitle = [],
			_data = [];
		$.each(data.items, function(i, v) {
			xtitle.push(v.name);
			_data.push(v.total);
		})
		if (type === 'line') {
			var chart = new Highcharts.Chart('H_chart_box', {
			    title: {
			        text: '购物车数据图表',
			        x: -20
			    },
		        subtitle: {
		            text: $('#J_level_time').val()
		        },
			    xAxis: {
			        categories: xtitle
			    },
			    yAxis: {
			        title: {
			            text: '购物车金额(元)'
			        },
			        plotLines: [{
			            value: 0,
			            width: 1,
			            color: '#808080'
			        }]
			    },
			    tooltip: {
			        valueSuffix: '元'
			    },
			    credits: {
			    	enabled: false
			    },
			    series: [{
			    	name: '购物车金额',
			    	data: _data
			    }]
			})
		}
		if (type === 'bar') {
			$('#H_chart_box').highcharts({
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '购物车数据图表'
		        },
		        xAxis: {
		            categories: xtitle,
		            crosshair: true
		        },
		        yAxis: {
		            min: 0,
		            title: {
		                text: '购物车金额 (元)'
		            }
		        },
			    credits: {
			    	enabled: false
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
		        series: [{
		            name: '购物车金额',
		            data: _data
		        }]
		    });
		}
	}
	// 切换图标
	$('#J_level_list span').on('click', function() {
		$(this).addClass('active').siblings('span').removeClass('active');
		var type = $(this).data('type');
		showLevelData(type)
	})

	// 各级区域条件搜索
	$('#J_level_btn').on('click', function() {
		var _date = $('#J_level_time').val().split(' 至 ');
		var user_level = $('#J_user_level').val();
		var level = $('#J_area_level').val();
		if (user_level == null) {
			alert('请选择搜索对象！')
			return
		}
		if (level == -1) {
			alert('请选择搜索级别！')
			return
		}
		var param = {
			start: _date[0],
			end: _date[1],
			user_level: user_level,
			level: level
		}
		searchLevelData(param)
	})

	// 获取精准查询数据
	function getDetailData(param) {
		addLoading($('#H_chart_box2'));
		requestUrl('/data/cart/accurate-search', 'GET', param, function(data) {
			removeLoading($('#H_chart_box2'));
			g.areaData = data;
			var type = $('#J_level_list2 span[class*="active"]').data('type');
			showAreaData(type);
			// 生成表格
			var table = '<tr>\
	                        <td>日期</td>';
	        for (var i = 0; i < data.items.length; i++) {
	        	table += '<td>' + data.items[i].name + '</td>';
	        }
	        table += '</tr>';
	        for (var i = 0; i < data.items.length; i++) {
	        	for (var j = 0; j < data.items[i].items.length; j++) {
	        		table += '<tr>\
	        					<td> ' + data.items[i].items[j].date.key + '</td>'
		        	for (var k = 0; k < data.items.length; k++) {
		        		table += '<td>' + data.items[k].items[i].total + '</td>'
		        	}
		        	table += '</tr>'
	        	}
	        }
	        $('#J_detail_box').html(table)
		})
	}
	function showAreaData(type) {
		var data = g.areaData;
		var xtitle = [],
			_data = [];
		$.each(data.items, function(i, v) {
			var data = [];
			$.each(v.items, function(index, val) {
				xtitle.push(val.date.key);
				data.push(val.total)
			})
			_data.push({name: v.name, data: data});
		})
		if (type === 'line') {
			var chart = new Highcharts.Chart('H_chart_box2', {
			    title: {
			        text: '购物车金额额数据图表',
			        x: -20
			    },
		        subtitle: {
		            text: $('#J_level_time').val()
		        },
			    xAxis: {
			        categories: xtitle
			    },
			    yAxis: {
			        title: {
			            text: '购物车金额(元)'
			        },
			        plotLines: [{
			            value: 0,
			            width: 1,
			            color: '#808080'
			        }]
			    },
			    tooltip: {
			        valueSuffix: '元'
			    },
			    credits: {
			    	enabled: false
			    },
			    series: _data
			})
		}
		if (type === 'bar') {
			$('#H_chart_box2').highcharts({
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '购物车金额额数据图表'
		        },
		        subtitle: {
		            text: $('#J_level_time').val()
		        },
		        xAxis: {
		            categories: xtitle,
		            crosshair: true
		        },
		        yAxis: {
		            min: 0,
		            title: {
		                text: '购物车金额 (元)'
		            }
		        },
			    credits: {
			    	enabled: false
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
		        series: _data
		    });
		}

	}
	// 切换图标
	$('#J_level_list2 span').on('click', function() {
		$(this).addClass('active').siblings('span').removeClass('active');
		var type = $(this).data('type');
		showAreaData(type)
	})
	// 立即搜索
	$('#J_detail_btn').on('click', function() {
		var area_id = [];
		$.each(g.areaList, function(i, val) {
			area_id.push(val)
		})
		if (area_id.length < 1) {
			alert('请至少追加一个区域！')
			return
		}
		var date = $('#J_detail_time').val().split(' 至 ');
		var user_level = $('#J_user_level2').val();
		if (user_level == null) {
			alert('请选择搜索对象！')
			return
		}
		var type = $('#J_type_box').val();
		var param = {
			area_id: area_id,
			user_level: user_level,
			start: date[0],
			end: date[1],
			by: type
		}
		getDetailData(param)
	})

})