$(function() {
    if (url('?show')) {
        $('#J_back_btn').removeClass('hidden')
    }

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

    //创建提示弹窗
    buildNewAlertInHere();
    var levels = {};
    function getLevel() {
        requestUrl('/leader/area/level', 'GET', '', function(data) {
            levels = data;
        }, function(data) {
            showAlert(data.data.errMsg)
        }, false)
    }
    getLevel();
    //获取区域列表
    function getArea(level, id) {
        requestUrl('/leader/area/list', 'GET', {parent_id: id}, function(data) {
            var html = '<div class="select-box">\
                            <select class="selectpicker J_area_box btn-group-xs" data-width="100%" data-haschild="' + data.has_child + '" data-level="' + level + '">\
                                <option value="-1">请选择' + levels[level] + '</option>';
            for (var i = 0; i < data.list.length; i++) {
                html += '<option value="' + data.list[i].id + '">' + data.list[i].name + '</option>'
            }
            html += '</select>\
                </div>'
            $('#J_info_area').append(html);
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker('show');
        }, function(data) {
            showAlert(data.data.errMsg);
        }, false)
    }
    //区域联动
    $('#J_info_area').on('change', 'select.J_area_box', function() {
        if (!$(this).data('haschild')) {return};
        var level = $(this).data('level') - 0;
        var val = $(this).val() - 0;
        if (val === -1) {return};
        $('select.J_area_box:gt(' + (level - 1) + ')').parents('.select-box').remove();
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('show');
        getArea(level + 1, val);
    })
    //获取账户信息
    function getInfo(id) {
        requestUrl('/leader/custom/info', 'GET', {account: id}, function(data) {
            $('#J_info_account').html(data.account);
            $('#J_info_name').html(data.nick_name);
            $('#J_info_mobile').html(data.mobile);
            $('#J_info_email').html(data.email);
            var area = '';
            $.each(data.area, function(v, k) {
                area += k.name + '&nbsp;&nbsp;'
            })
            $('#J_info_area').html(area);
            //绑定修改按钮
            $('#J_area_edit').off().on('click.edit', function() {
                $('#J_area_edit').addClass('disabled');
                var len = Object.keys(data.area).length;
                $('#J_info_area').html('');
                getArea(1, 0);
                for (var i = 1; i < len; i++) {
                    getArea(i + 1, data.area[i].id);
                    $('select.J_area_box').eq(i - 1).val(data.area[i].id);
                }
                $('select.J_area_box').eq(len - 1).val(data.area[len].id);
                $('.selectpicker').selectpicker('refresh');
                $('.selectpicker').selectpicker('show');
                $('#J_area_edit').addClass('hidden');
                $('#J_area_edit').removeClass('disabled');
                $('#J_area_sure').removeClass('hidden');
            })
            // 确定修改
            $('#J_area_sure').off().on('click', function() {
                $('#J_area_sure').addClass('disabled');
                var area_id = $('select.J_area_box:last').val();
                if (area_id === '-1') {
                    showAlert('请选择正确区域！');
                    return;
                }
                var data = {
                    account: url('?account'),
                    area_id: area_id
                }
                requestUrl('/leader/custom/area', 'POST', data, function(data) {
                    showAlert('修改成功！');
                    getInfo(url('?account'));
                    $('#J_area_sure').addClass('hidden');
                    $('#J_area_sure').removeClass('disabled');
                    $('#J_area_edit').removeClass('hidden');
                })
            })
        })
    }
    getInfo(url('?account'));
    //返回按钮初始化
    $('#J_back_btn').attr('href', '/leader/custom-list' + location.search);

    //获取门店消费额
    function getAchievement(id) {
        requestUrl('/leader/custom/achievement', 'GET', {account: id}, function(data) {
            $('#J_user_yesterday').html('￥' + data.yesterday.toFixed(2));
            $('#J_user_life').html('￥' + data.life.toFixed(2));
            var _money = {};
            _money.normal = data.normal;
            _money.refund = data.refund;
            _money.reject = data.reject;
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
                    name: '访问来源',
                    type: 'pie',
                    radius: '55%',
                    center: ['30%', '60%'],
                    data: [
                        { value: _money.normal, name: '正常金额' },
                        { value: _money.refund, name: '退换货金额'},
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
    getAchievement(url('?account'));
    //获取用户业绩
    var _tpl = juicer($('#J_tpl_data').html());
    function money(data) {
        return (data.normal + data.refund + data.reject).toFixed(2)
    }
    juicer.register('money', money);
    function getChart(id, start, end, type) {
        var data = {
            account: id,
            date_from: start,
            date_to: end,
            date_type: type
        }
        requestUrl('/leader/custom/chart', 'GET', data, function(data) {
            var html = _tpl.render(data);
            $('#J_user_data').html(html);
        })
    }
    $('#date_tab span').on('click', function() {
       $('#date_tab span').removeClass('active'); 
       $(this).addClass('active'); 
    })
    $('#J_search_data').on('click', function() {
        var start = $('input.J_search_timeStart').val();
        var end = $('input.J_search_timeEnd').val();
        var type = $('#date_tab span[class*="active"]').data('id');
        var id = url('?account');
        if (id == '' || id == undefined) return;
        getChart(id, start, end, type);
    })
})
