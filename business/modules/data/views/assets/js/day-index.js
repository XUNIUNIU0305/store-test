$(function () {
	// 数据总览
	function getTotalData() {
		addLoading($('.J_total_loading_box'));
		requestUrl('/data/day/total-preview', 'GET', '', function(data) {
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

	// 热销单品
	function getTopProduct() {
		requestUrl('/data/day/top-product', 'GET', '', function(data) {
			if (!data.items) return;
			$('#J_hot_pro_img').attr('src', data.product.image);
			if (data.product.price.min === data.product.price.max) {
				$('#J_hot_pro_price').html(data.product.price.min)
			} else {
				$('#J_hot_pro_price').html(data.product.price.min + '-' + data.product.price.max)
			}
			$('#J_hot_pro_title').html(data.product.title);
			$('#J_hot_pro_count').html(data.product.total);
			// 详情
			var detail = '',
				_max = data.items[0].total;
			$.each(data.items, function(i, val) {
				detail += '<tr>\
			                    <td>' + (i - 0 + 1) + '</td>\
			                    <td>' + val.attributes + '</td>\
			                    <td>' + val.total + '</td>\
			                    <td><span style="width: ' + (val.total / _max * 100) + '%;"></span></td>\
			                    <td></td>\
			                </tr>'
			})
			$('#J_hot_pro_detail').html(detail);
		})
	}
	getTopProduct()

	// 销售冠军
	function getTopPrice() {
		requestUrl('/data/day/top-price', 'GET', '', function(data) {
			if (!data.items) return;
			$('#J_sell_img').attr('src', data.product.image);
			if (data.product.price.min === data.product.price.max) {
				$('#J_sell_price').html(data.product.price.min)
			} else {
				$('#J_sell_price').html(data.product.price.min + '-' + data.product.price.max)
			}
			$('#J_sell_title').html(data.product.title);
			$('#J_sell_total').html(data.product.total);
			// 详情
			var detail = '',
				_max = data.items[0].total;
			$.each(data.items, function(i, val) {
				detail += '<tr>\
			                    <td>' + (i - 0 + 1) + '</td>\
			                    <td>' + val.attributes + '</td>\
			                    <td>' + val.total + '</td>\
			                    <td><span style="width: ' + (val.total / _max * 100) + '%;"></span></td>\
			                    <td></td>\
			                </tr>'
			})
			$('#J_sell_detail').html(detail);
		})
	}
	getTopPrice()

	// 喜爱品牌
	var tpl_pro = $('#J_tpl_pro').html();
	function price(data) {
		if (data.min_price === data.max_price) {
			return data.min_price
		} else {
			return data.min_price + '-' + data.max_price
		}
	}
	juicer.register('price', price);
	function getTopBrand() {
		requestUrl('/data/day/top-brand', 'GET', '', function(data) {
			if (!data.items) return;
			$('#J_brand_img').attr('src', data.supply.image);
			$('#J_brand_name').html(data.supply.name);
			$('#J_love_items').html(juicer(tpl_pro, data.items));
		})
	}
	getTopBrand()

	// 详情切换
	$('.J_pro_buy_ranking').on('click', function() {
		var id = $(this).data('id');
		$('.J_pro_ranking_info').addClass('hidden');
		$('.J_pro_ranking_info').eq(id).removeClass('hidden');
	})
	$('.close').on('click', function() {
		$(this).parents('.J_pro_ranking_info').addClass('hidden');
	})

	// 区域排名
	function getAreaRanking(level) {
		addLoading($('#area_loading_box'));
		requestUrl('/data/day/area', 'GET', {level: level}, function(data) {
			removeLoading($('#area_loading_box'));
			$.each(data.items, function(i, val) {
				$('.ranking-area .J_area_name').eq(i).text(val.area.name).attr('title', val.area.name);
				$('.ranking-area .J_area_price').eq(i).find('span').text(val.soles);
				$('.ranking-area .J_area_unit').eq(i).find('span').text(val.unitPrice);
				$('.ranking-area .J_area_total_account').eq(i).find('span').text(val.totalAccount);
				$('.ranking-area .J_area_total_fee').eq(i).find('span').text(val.totalFeeAccount);
				$('.ranking-area .J_area_day_fee').eq(i).find('span').text(val.dayFeeAccount);
			})
		})
	}
	getAreaRanking(1)

	// 切换排行
	$('#J_ranking_tab').on('click', 'span', function() {
		$(this).addClass('active').siblings('span').removeClass('active');
		var type = $(this).data('type');
		if (type === "area") {
			$('.ranking-area').removeClass('hidden');
			$('.ranking-store').addClass('hidden');
		} else {
			$('.ranking-area').addClass('hidden');
			$('.ranking-store').removeClass('hidden');
		}
	})
	// 切换区域
	$('#J_area_tabs').on('click', 'span', function() {
		$(this).addClass('active').siblings().removeClass('active');
		var level = $(this).data('level');
		getAreaRanking(level);
	})

	// 门店排名
	var tpl_store = $('#J_tpl_store').html();
	function getStoreRanking() {
		addLoading($('#store_loading_box'));
		requestUrl('/data/day/store', 'GET', '', function(data) {
			removeLoading($('#store_loading_box'));
			$('#J_store_ranking').html(juicer(tpl_store, data));
		})
	}
	getStoreRanking()



});
