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
        $('.J_search_timeStart').datepicker('show')
    })
    $('.J_timeEnd_show').on('click', function() {
        $('.J_search_timeEnd').datepicker('show')
    })


    var _userId = url('?id'),
        _money = {};


	//获取用户信息
    function getUserInfo(id) {
        requestUrl('/account/index/identity', 'GET', {user_id: id}, function(data) {
            $('#J_user_name').html(data.name);   
            $('#J_user_mobile').html(data.mobile);   
            $('#J_user_role').html(data.role);
            var area = '';
            $.each(data.area, function(i, val) {
                area += val + '&nbsp;&nbsp;'
            })   
            $('#J_user_area').html(area);   
            $('#J_user_custom').html(data.custom_quantity);   
            $('#J_user_order').html(data.order_quantity);   
        })
    }
    //获取用户生涯业绩
    function getAchievements(id) {
        requestUrl('/account/index/achievement', 'GET', {user_id: id}, function(data) {
            $('#J_user_yestarday').html(data.yestarday.toFixed(2));
            $('#J_user_life').html(data.life.toFixed(2));
            $('#J_user_position').html(data.position.toFixed(2));
        })
    }
    //获取用户在职的订单业绩
    function getPosition(id) {
        requestUrl('/account/index/position', 'GET', {user_id: id}, function(data) {
            _money.normal = data.normal;
            _money.refund = data.refund;
            _money.reject = data.reject;
            //饼图
            var chart_legend = echarts.init(document.getElementById('J_order_legend'));
            var option = {
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    right: 'right',
                    bottom: 'bottom',
                    data: ['正常金额', '退换货金额', '驳回金额']
                },
                series: [{
                    name: '金额对比',
                    type: 'pie',
                    radius: '55%',
                    center: ['30%', '60%'],
                    data: [
                        { value: _money.normal, name: '正常金额' },
                        { value: _money.refund, name: '退换货金额' },
                        { value: _money.reject, name: '驳回金额' }
                    ],
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }]
            };
            chart_legend.setOption(option);
        })
    }
    //初始化页面数据
    if (_userId != undefined) {
        getUserInfo(_userId);
        getAchievements(_userId);
        getPosition(_userId);
        $('.J_back_btn').removeClass('hidden').find('a').on('click', function() {
            window.history.back()
        })
    } else {
        getUserInfo();
        getAchievements();
        getPosition();
    }
    //获取用户业绩
    var _tpl = juicer($('#J_tpl_data').html());
    function money(data) {
        return (data.normal + data.refund + data.reject).toFixed(2)
    }
    juicer.register('money', money);
	function getChart(start, end, id, type) {
        var data = {
            date_from: start,
            date_to: end,
            user_id: id,
            date_type: type
        }
        requestUrl('/account/index/chart', 'GET', data, function(data) {
            //表格
            var html = _tpl.render(data);
            $('#J_user_data').html(html);
            //柱状图
            var _date = [],
                _data = [];
            $.each(data, function(i, val) {
                _date.push(val.date);
                _data.push((val.normal + val.refund + val.reject).toFixed(2));
            })
            var barChart = echarts.init(document.getElementById('J_bar_chart'));
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
                    data: _date
                },
                yAxis: {
                    type: 'value',
                    boundaryGap: [0, '100%']
                },
                dataZoom: [{
                    type: 'inside',
                    disabled: true
                }],
                series: [{
                    name: ['销售金额'],
                    type: 'bar',
                    silent: true,
                    data: _data
                }]
            };
            barChart.setOption(optionBigBar)
        })
    }
    $('#date_tab span').on('click', function() {
       $('#date_tab span').removeClass('active'); 
       $(this).addClass('active'); 
    })
    $('#J_search_btn').on('click', function() {
        var start = $('input.J_search_timeStart').val();
        var end = $('input.J_search_timeEnd').val();
        var type = $('#date_tab span[class*="active"]').data('id');
        getChart(start, end, _userId, type);
    })

    // 获取账户余额
    function getBalance() {
        requestUrl('/user/balance', 'GET', '', function(data) {
            $('#J_user_balance').html('￥' + parseFloat(data.rmb).toFixed(2));
        })
    }
    getBalance()
})