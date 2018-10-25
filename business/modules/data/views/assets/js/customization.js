$(function() {
	// 暂存数据
	g = {
		data: [],
		custom: [],
		uncustom: [],
		proData: []
	}

	var scrolls = [];
    $('.iscroll_container').each(function () {
        scrolls.push(new IScroll(this, {
            mouseWheel: true,
            scrollbars: true,
            scrollbars: 'custom'
        }))
    })
    function refreshScroll() {
		setTimeout(function() {
			scrolls.forEach(function (scroll) {
				scroll.refresh();
			})
		}, 300)
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

    // 获取用户级别
	function getUserLevel() {
		requestUrl('/data/home/user-level', 'GET', '', function(data) {
			var options = '';
			$.each(data, function(i, v) {
				options += '<option value="' + i + '">' + v + '</option>'
			})
			$('#J_user_level').html(options).find('option').eq(0).attr('selected', 'selected');
			refresh($('#J_user_level'));
			refresh($('#J_user_level2'));
		})
	}
	getUserLevel()

	// 获取数据分析
	var data_tpl = $('#J_tpl_data').html();
	var pro_tpl = $('#J_tpl_pro').html();
	function price(data) {
		if (data.min === data.max) {
			return data.min
		} else {
			return data.min + ' - ' + data.max
		}
	}
	juicer.register('price', price);
	function getData(params) {
		addLoading($('#H_chart_box'));
		requestUrl('/data/customization/search', 'GET', params, function(data) {
			removeLoading($('#H_chart_box'));
			g.data = data.items;
			g.custom = data.isProducts;
			g.uncustom = data.products;
			var type = $('#J_data_type span[class*="active"]').data('type')
			showData(type)
			// 生成表格
			$('#J_data_table').html(juicer(data_tpl, data.items));
			// 商品
			$('#J_pro_box').html(juicer(pro_tpl, data.isProducts));
			refreshScroll()
		})
	}
	getData({start: '2017-08-01'})
	function showData(type) {
		var items = g.data;
		var xtitle = [],
			_data = [],
			_data2 = [];
		$.each(items, function(i, v) {
			xtitle.push(v.date.key);
			_data.push(v.customizationTotal);
			_data2.push(v.total);
		})
		if (type === 'line') {
			var chart = new Highcharts.Chart('H_chart_box', {
			    title: {
			        text: '销售额数据图表',
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
			            text: '销售额(元)'
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
		            name: '定制商品',
		            data: _data
		        },{
		        	name: '非定制商品',
		        	data: _data2
		        }]
			})
		}
		if (type === 'bar') {
			$('#H_chart_box').highcharts({
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '销售额数据图表'
		        },
		        xAxis: {
		            categories: xtitle,
		            crosshair: true
		        },
		        yAxis: {
		            min: 0,
		            title: {
		                text: '销售额 (元)'
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
		            name: '定制商品',
		            data: _data
		        },{
		        	name: '非定制商品',
		        	data: _data2
		        }]
		    });
		}
	}
	// 切换状态
	$('#J_data_type span').on('click', function() {
		$(this).addClass('active').siblings().removeClass('active');
		var type = $(this).data('type');
		showData(type)
	})
	$('#J_pro_type span').on('click', function() {
		var type = $(this).data('type');
		$(this).addClass('active').siblings().removeClass('active');
		if (type === 'is') {
			$('#J_pro_box').html(juicer(pro_tpl, g.custom));
		}
		if (type === 'no') {
			$('#J_pro_box').html(juicer(pro_tpl, g.uncustom));
		}
		refreshScroll()
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

	// 搜索总数据
	$('#J_data_btn').on('click', function() {
		var _date = $('#J_data_time').val().split(' 至 ');
		var user_level = $('#J_user_level').val();
		if (user_level == null) {
			alert('请选择搜索对象！')
			return
		}
		var type = $('#J_type_box').val();
		var param = {
			start: _date[0],
			end: _date[1],
			user_level: user_level,
			by: type
		}
		getData(param)
	})

	// 商品详情
	$('#product_chart_modal').on('shown.bs.modal', function(e) {
		var id = $(e.relatedTarget).data('id');
		$('#product_chart_modal').data('id', id);
		getProData({id: id})
	})
	$('#J_pro_btn').on('click', function() {
		var id = $('#product_chart_modal').data('id')
		getProData({id: id})
	})
	// 获取商品数据
	function getProData(params) {
		addLoading($('#H_pro_box'));
		var _date = $('#J_data_time').val().split(' 至 ');
		var user_level = $('#J_user_level').val();
		if (user_level == null) {
			alert('请选择搜索对象！')
			return
		}
		var level = $('#J_area_level').val();
		var type = $('#J_type_box').val();
		var param = {
			start: _date[0],
			end: _date[1],
			user_level: user_level,
			by: type,
			level: level
		}
		$.extend(param, params);
		requestUrl('/data/customization/product-search', 'GET', param, function(data) {
			removeLoading($('#H_pro_box'));
			g.proData = data.items;
			var type = $('#J_chart_type span[class*="active"]').data('type')
			showProData(type)
		})
	}
	function showProData(type) {
		var items = g.proData;
		var xtitle = [],
			_data = [];
		$.each(items, function(i, v) {
			xtitle.push(v.name);
			_data.push(v.total);
		})
		if (type === 'line') {
			var chart = new Highcharts.Chart('H_pro_box', {
			    title: {
			        text: '销售额数据图表',
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
			            text: '销售额(元)'
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
		            name: '定制销售',
		            data: _data
		        }]
			})
		}
		if (type === 'bar') {
			$('#H_pro_box').highcharts({
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '销售额数据图表'
		        },
		        xAxis: {
		            categories: xtitle,
		            crosshair: true
		        },
		        yAxis: {
		            min: 0,
		            title: {
		                text: '销售额 (元)'
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
		            name: '商品销售',
		            data: _data
		        }]
		    });
		}
	}
	// 切换表格
	$('#J_chart_type span').on('click', function() {
		var type = $(this).data('type');
		$(this).addClass('active').siblings().removeClass('active');
		showProData(type);
	})
})