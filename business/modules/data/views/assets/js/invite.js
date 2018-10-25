$(function() {

	// 暂存全局数据
	var g = {
		items: [],
		tpl_pro: $('#J_tpl_pro').html(),
		tpl_area: $('#J_tpl_area').html(),
		tpl_user: $('#J_tpl_user').html(),
		tpl_tab: $('#J_tpl_tab').html()
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
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		refreshScroll()
	})

	// 获取邀请门店数据
	function getInviteData(params) {
		$('#J_subhead').html($('#J_invite_time').val());
		addLoading($('#H_chart_box'));
		requestUrl('/data/invite/search', 'GET', params, function(data) {
			removeLoading($('#H_chart_box'));
			$('#J_passTime').html(data.passTime);
			$('#J_averFee').html(data.averFee + '元');
			$('#J_averNum').html(data.averNum + '人');
			$('#J_unitPrice').html(data.unitPrice + '元/人');
			g.items = data.items;
			
			var type = $('#J_chart_type span[class*="active"]').data('type');
			showInvite(type);
			// 生成列表
			var _area = [];
			$.each(data.hotArea, function(i, v) {
				if (v.product.id) {
                 	_area.push(v)
				}
			})

			$('#J_pro_list').html(juicer(g.tpl_pro, data.hotProducts));
			$('#J_area_box').html(juicer(g.tpl_area, _area));
			$('#J_user_list').html(juicer(g.tpl_user, data.users));
			refreshScroll()

			// 生成表格
			$('#J_table_box').html(juicer(g.tpl_tab, data.items))
		})
	}
	function showInvite(type) {
		var items = g.items;
		var xtitle = [],
			_data = [],
			_passNum = [],
			_inviteNum = [];
		$.each(items, function(i, v) {
			xtitle.push(v.date.key);
			_data.push(v.total);
			_passNum.push(v.passNum);
			_inviteNum.push(v.inviteNum);
		})
		if (type === 'line') {
			$('#H_chart_box').highcharts({
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
		            data: _data,
		            tooltip: {
		                valueSuffix: ' 元'
		            }
		        }, {
		            name: '邀请人数',
		            type: 'spline',
		            data: _inviteNum,
		            tooltip: {
		                valueSuffix: ' 人'
		            }
		        }, {
		            name: '开通人数',
		            type: 'spline',
		            data: _passNum,
		            tooltip: {
		                valueSuffix: ' 人'
		            }
		        }]
		    });
		}
	}

	// 搜索
	$('#J_search_btn').on('click', function() {
		var source = $('#J_invite_source').val();
		var date = $('#J_invite_time').val().split(' 至 ');
		var by = $('#J_by_type').val();
		getInviteData({
			start: date[0],
			end: date[1],
			by: by,
			source: source
		})
	})
	$('#J_search_btn').click();
})