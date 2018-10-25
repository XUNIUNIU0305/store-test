$(function() {

	// 数据暂存
	var _dataTmp = {
		totalMoney: {},
		carMoney: {},
		custom: {},
		scattergram: {},
		store: {}
	}
	// scroll
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
	Highcharts.setOptions({
		lang: {
			shortMonths: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']
		}
	})

	// 获取消费转化率
	function getConversion(params) {
		addLoading($('#conversion_loading_box'));
		var date = $('.J_conversion_time').val();
		var dateS = date.split(' 至 ');
		var data = {
			start: dateS[0],
			end: dateS[1]
		}
		$.extend(data, params);
		requestUrl('/data/home/conversion-rate', 'GET', data, function(data) {
			removeLoading($('#conversion_loading_box'));
			$('#J_total_conversion').html(data.totalConversionRate);
			var _data = [data.createNum, data.payNum, data.finishNum, data.secondConsumeNum, data.threeConsumeNum];
			var _dataConversion = [data.createRate, data.totalConversionRate, data.finishRate, data.secondConsumeRate, data.threeConsumeRate];
			$('#J_conversion_list li').each(function(i) {
				$(this).html(_dataConversion[i] + '%')
			})
			// 消费转化率展示
		    $('#H_conversion').highcharts({
		        chart: {
		            type: 'bar'
		        },
		        title: {
		            text: ''
		        },
		        xAxis: {
		            categories: ['下单人数', '支付人数', '完成订单人数', '二次消费人数', '三次消费人数'],
		            title: {
		                text: null
		            }
		        },
		        yAxis: {
		            min: 0,
		            title: {
		                text: '人数(人)',
		                align: 'high'
		            },
		            labels: {
		                overflow: 'justify'
		            }
		        },
		        tooltip: {
		            enabled: false
		        },
		        plotOptions: {
		            bar: {
		                dataLabels: {
		                    enabled: true,
		                    allowOverlap: true
		                }
		            }
		        },
		        credits: {
		            enabled: false
		        },
		        legend: {
		            enabled: false
		        },
		        series: [{
		            name: '',
		            data: _data
		        }]
		    });
		})
	}
	getConversion()

	// 获取消费总额
	function getTotalMoney(params) {
		addLoading($('#total_loading_box'));
		var date = $('.J_total_time').val();
		var dateS = date.split(' 至 ');
		var data = {
			start: dateS[0],
			end: dateS[1],
			by: 'day'
		}
		$.extend(data, params);
		requestUrl('/data/home/total-consume', 'GET', data, function(data) {
			removeLoading($('#total_loading_box'));
			_dataTmp.totalMoney = data;
			var type = $('[data-method="totalMoney"] span[class*="active"]').data('status');
			showTotalMoney(type);
			$('#J_total_money').html(data.total.toFixed(2));
		})
	}
	getTotalMoney()
	function showTotalMoney(status) {
		// if (_dataTmp.totalMoney.items.length < 1) return;
		var xtitle = [],
			price = [];
		$.each(_dataTmp.totalMoney.items, function(i, value) {
			xtitle.push(value.date.key);
			price.push(value.total);
		})
		if (status === 'tb') {
			var tpl = $('#J_tpl_money').html();
			$('#J_total_table').html(juicer(tpl, _dataTmp.totalMoney));
			$('#H_price_total').addClass('hidden');
			$('#H_price_total').siblings('.tb-box').removeClass('hidden');
			refreshScroll()
		} else {
			$('#H_price_total').removeClass('hidden').siblings('.tb-box').addClass('hidden');
		}
		// 折线图
		if (status === 'line') {
			var chart = new Highcharts.Chart('H_price_total', {
			    title: {
			        text: ''
			    },
			    xAxis: {
			        categories: xtitle
			    },
			    yAxis: {
			        title: {
			            text: '金额 (元)'
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
			    legend: {
			        enabled: false
			    },
		        credits: {
		            enabled: false
		        },
			    series: [{
			        name: '消费',
			        data: price
			    }]
			});
		}
		// 柱状图
		if (status === 'bar') {
			$('#H_price_total').highcharts({
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: ''
		        },
		        xAxis: {
		            categories: xtitle,
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
			        enabled: false
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
		            name: '消费',
		            data: price
		        }]
		    });
		}
	}

	// 获取购物车内金额
	function getCarMoney(params) {
		addLoading($('#car_loading_box'));
		var date = $('.J_car_time').val();
		var dateS = date.split(' 至 ');
		var data = {
			start: dateS[0],
			end: dateS[1],
			by: 'day'
		}
		$.extend(data, params);
		requestUrl('/data/home/shop-cart', 'GET', data, function(data) {
			removeLoading($('#car_loading_box'));
			_dataTmp.carMoney = data;
			var type = $('[data-method="carMoney"] span[class*="active"]').data('status');
			showCarMoney(type);
			$('#J_car_price').html(data.total.toFixed(2));
		})
	}
	getCarMoney()
	function showCarMoney(status) {
		// if (_dataTmp.carMoney.items.length < 1) return;
		var xtitle = [],
			price = [];
		$.each(_dataTmp.carMoney.items, function(i, value) {
			xtitle.push(value.date.key);
			price.push(value.total);
		})
		if (status === 'tb') {
			var tpl = $('#J_tpl_money').html();
			$('#J_car_table').html(juicer(tpl, _dataTmp.carMoney));
			$('#H_car_money').addClass('hidden');
			$('#H_car_money').siblings('.tb-box').removeClass('hidden');
			refreshScroll()
		} else {
			$('#H_car_money').removeClass('hidden').siblings('.tb-box').addClass('hidden');
		}
		if (status === 'line') {
			var chart = new Highcharts.Chart('H_car_money', {
			    title: {
			        text: ''
			    },
			    xAxis: {
			        categories: xtitle
			    },
			    yAxis: {
			        title: {
			            text: '金额 (元)'
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
			    legend: {
			        enabled: false
			    },
		        credits: {
		            enabled: false
		        },
			    series: [{
			        name: '消费',
			        data: price
			    }]
			});
		}
		if (status === 'bar') {
			$('#H_car_money').highcharts({
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: ''
		        },
		        xAxis: {
		            categories: xtitle,
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
			        enabled: false
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
		            name: '消费',
		            data: price
		        }]
		    });
		}
	}

	// 获取定制与非定制商品
	function getCustom(params) {
		addLoading($('#custom_loading_box'))
		var date = $('.J_custom_time').val();
		var dateS = date.split(' 至 ');
		var data = {
			start: dateS[0],
			end: dateS[1],
			by: 'day'
		}
		$.extend(data, params);
		requestUrl('/data/home/is-custom', 'GET', data, function(data) {
			removeLoading($('#custom_loading_box'));
			_dataTmp.custom = data;
			var type = $('[data-method="custom"] span[class*="active"]').data('status');
			showCustom(type);
			$('#J_uncustom_price').html(data.normal_total.toFixed(2));
			$('#J_custom_price').html(data.customization_total.toFixed(2));
		})
	}
	getCustom()
	function showCustom(status) {
		// if (_dataTmp.custom.items.length < 1) return;
		var xtitle = [],
			custom_price = [],
			uncustom_price = [];
		$.each(_dataTmp.custom.items, function(i, value) {
			xtitle.push(value.date.key);
			uncustom_price.push(value.normal);
			custom_price.push(value.customization);
		})
		if (status === 'tb') {
			var tpl = $('#J_tpl_custom').html();
			$('#J_custom_table').html(juicer(tpl, _dataTmp.custom));
			$('#H_custom').addClass('hidden');
			$('#H_custom').siblings('.tb-box').removeClass('hidden');
			refreshScroll()
		} else {
			$('#H_custom').removeClass('hidden').siblings('.tb-box').addClass('hidden');
		}
		if (status === 'line') {
			var chart = new Highcharts.Chart('H_custom', {
			    title: {
			        text: ''
			    },
			    xAxis: {
			        categories: xtitle
			    },
			    yAxis: {
			        title: {
			            text: '金额 (元)'
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
			    legend: {
			        
			    },
		        credits: {
		            enabled: false
		        },
			    series: [{
			        name: '定制商品',
			        data: custom_price
			    },{
			        name: '非定制商品',
			        data: uncustom_price
			    }]
			});
		}
		if (status === 'bar') {
			$('#H_custom').highcharts({
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: ''
		        },
		        xAxis: {
		            categories: xtitle,
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
			        enabled: false
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
		            name: '定制商品',
		            data: custom_price
		        }, {
		        	name: '非定制商品',
		        	data: uncustom_price
		        }]
		    });
		}
		if (status === 'tb') {}
	}

	// 获取下单金额与时间分布
	function getScattergram(params) {
		addLoading($('#buy_loading_box'));
		var date = $('.J_scattergram_time').val();
		var dateS = date.split(' 至 ');
		var data = {
			start: dateS[0],
			end: dateS[1],
			by: 'day'
		}
		$.extend(data, params);
		requestUrl('/data/home/time-amount', 'GET', data, function(data) {
			removeLoading($('#buy_loading_box'));
			_dataTmp.scattergram = data;
			var type = $('[data-method="scattergram"] span[class*="active"]').data('status');
			showScattergram(type)
		})
	}
	getScattergram()
	function showScattergram(status) {
		// if (_dataTmp.scattergram.items.length < 1) return;

		if (status === 'tb') {
			var tpl = $('#J_tpl_money').html();
			$('#J_scattergram_table').html(juicer(tpl, _dataTmp.scattergram));
			$('#H_scattergram').addClass('hidden');
			$('#H_scattergram').siblings('.tb-box').removeClass('hidden');
			refreshScroll()
		} else {
			$('#H_scattergram').removeClass('hidden').siblings('.tb-box').addClass('hidden');
		}

		if (status === 'dot') {
			var items = _dataTmp.scattergram.items;
			if (items.length < 1) return;
			var _data = [],
				_start = new Date(items[0].date.date).getTime();
			var type = $('.item-select[data-method="scattergram"] .J_type_title').data('type');
			if (type == 'hour') {
				var interval = 3600 * 1000;
				var format = {hour: '%H:%M'};
			}
			if (type == 'day') {
				var interval = 24 * 3600 * 1000;
				var format = {day: '%b-%e'};
			}
			if (type == 'week') {
				var interval = 7 * 24 * 3600 * 1000;
				var format = {week: '%b-%e'};
			}
			if (type == 'month') {
				var interval = 30 * 7 * 24 * 3600 * 1000;
				var format = {month: '%y-%b'};
			}
			$.each(items, function(i, val) {
				$.each(val.order, function(index, value) {
					var _date = new Date(value.date).getTime();
					_data.push([_date, value.total_fee])
				})
			})
			
			$('#H_scattergram').highcharts({
		        chart: {
		            type: 'scatter',
		            zoomType: 'xy'
		        },
		        title: {
		            text: ''
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
		            enabled: false
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
		            color: 'rgba(223, 83, 83, .5)',
		            data: _data,
			        pointStart: _start,
	            	pointInterval: interval,
	            	pointIntervalUnit: type
	            }]
		    });
		}
	}

	// 获取邀请门店
	function getStore(params) {
		addLoading($('#invite_loading_box'));
		var date = $('.J_store_time').val();
		var dateS = date.split(' 至 ');
		var data = {
			start: dateS[0],
			end: dateS[1],
			by: 'day'
		}
		$.extend(data, params);
		requestUrl('/data/home/store', 'GET', data, function(data) {
			removeLoading($('#invite_loading_box'));
			_dataTmp.store = data;
			var type = $('[data-method="store"] span[class*="active"]').data('status');
			showStore(type);
		})
	}
	getStore()
	function showStore(status) {
		// if (_dataTmp.store.items.length < 1) return;
		var xtitle = [],
			price = [],
			invite = [],
			pass = [];
		$.each(_dataTmp.store.items, function(i, value) {
			xtitle.push(value.date.key);
			price.push(value.total);
			invite.push(value.inviteNum);
			pass.push(value.openedNum);
		})
		if (status === 'tb') {
			var tpl = $('#J_tpl_store').html();
			$('#J_store_table').html(juicer(tpl, _dataTmp.store));
			$('#H_store').addClass('hidden');
			$('#H_store').siblings('.tb-box').removeClass('hidden');
			refreshScroll()
		} else {
			$('#H_store').removeClass('hidden').siblings('.tb-box').addClass('hidden');
		}
		if (status === 'line') {
			$('#H_store').highcharts({
		        chart: {
		            zoomType: 'xy'
		        },
		        title: {
		            text: ''
		        },
		        xAxis: [{
		            categories: xtitle,
		            crosshair: true
		        }],
		        yAxis: [{ // Primary yAxis
		            labels: {
		                format: '{value}人',
		                style: {
		                    color: Highcharts.getOptions().colors[1]
		                }
		            },
		            title: {
		                text: '人数',
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
		        }, { // Secondary yAxis
		            title: {
		                text: '',
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
		            data: price,
		            tooltip: {
		                valueSuffix: ' 元'
		            }
		        }, {
		            name: '邀请人数',
		            type: 'spline',
		            data: invite,
		            tooltip: {
		                valueSuffix: ' 人'
		            }
		        }, {
		            name: '开通人数',
		            type: 'spline',
		            data: pass,
		            tooltip: {
		                valueSuffix: ' 人'
		            }
		        }]
		    });
		}
	}

	// 切换显示类型
	$('.item-tabs').on('click', 'span', function() {
		$(this).addClass('active').siblings().removeClass('active');
		var status = $(this).data('status');
		var method = $(this).parent('.item-tabs').data('method');
		if (method === 'totalConversion') {}
		if (method === 'totalMoney') {
			showTotalMoney(status)
		}
		if (method === 'carMoney') {
			showCarMoney(status)
		}
		if (method === 'custom') {
			showCustom(status)
		}
		if (method === 'scattergram') {
			showScattergram(status)
		}
		if (method === 'store') {
			showStore(status )
		}
	})

	// 切换展示类型
	$('.dropdown-menu li').on('click', function(e) {
		e.preventDefault();
		var type = $(this).data('type');
		var title = $(this).data('title');
		var method = $(this).parents('.item-select').data('method');
		$(this).parents('.btn-group').find('.J_type_title').html(title).data('type', type);
		if (method === 'totalMoney') {
			getTotalMoney({by: type})
		}
		if (method === 'carMoney') {
			getCarMoney({by: type})
		}
		if (method === 'custom') {
			getCustom({by: type})
		}
		if (method === 'scattergram') {
			getScattergram({by: type})
		}
		if (method === 'store') {
			getStore({by: type} )
		}
	})

	// 切换时间
	$('.J_conversion_time').on('apply.daterangepicker', function() {
		getConversion()
	})
	$('.J_total_time').on('apply.daterangepicker', function() {
		var type = $('[data-method="totalMoney"] .J_type_title').data('type');
		getTotalMoney({by: type})
	})
	$('.J_car_time').on('apply.daterangepicker', function() {
		var type = $('[data-method="carMoney"] .J_type_title').data('type');
		getCarMoney({by: type})
	})
	$('.J_custom_time').on('apply.daterangepicker', function() {
		var type = $('[data-method="custom"] .J_type_title').data('type');
		getCustom({by: type})
	})
	$('.J_scattergram_time').on('apply.daterangepicker', function() {
		var type = $('[data-method="scattergram"] .J_type_title').data('type');
		getScattergram({by: type})
	})
	$('.J_store_time').on('apply.daterangepicker', function() {
		var type = $('[data-method="store"] .J_type_title').data('type');
		getStore({by: type})
	})
})