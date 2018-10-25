$(function () {
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
        language: 'zh-CN'
    });
    //初始化时间
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
    // $('input.J_search_timeEnd').val(nowTime);

    var g = {
        _page: 1,
        _size: 10,
        _pageBox: $('#J_stream_page')
    }
    //获取流水记录
    var _tpl = $('#J_tpl_list').html();
    function status(data) {
        if (data == 1) {
            return '待提交'
        }
        if (data == 2) {
            return '待审核'
        }
        if (data == 3) {
            return '审核失败'
        }
        if (data == 4) {
            return '待开通'
        }
        if (data == 5) {
            return '已开通'
        }
    }
    juicer.register('status', status)
    function getStream(params) {
        var _data = {
            account: params.account,
            mobile: params.mobile,
            promoter_id: params.promoter_id,
            pay_time: params.pay_time,
            auth_time: params.auth_time,
            valid_time: params.valid_time,
            current_page: params.page,
            page_size: params.size
        }
        requestUrl('/site/promoter/stream-log', 'GET', _data, function(data) {
            $('#J_stream_page').html('');
            $('#J_stream_list').html(juicer(_tpl, data));
            //生成分页
            if (data.data.length > 1) {
                pagingBuilder.build(g._pageBox, params.page, params.size, data.total_count);
                pagingBuilder.click(g._pageBox, function(page) {
                    params.page = page
                    getStream(params)
                })
            }
        })
    }
    getStream({
        page: g._page,
        size: g._size
    })

    //获取统计数据
    function getData() {
        requestUrl('/site/promoter/stream-count', 'GET', '', function(data) {
            $('#J_data_num').text(data.count);
            $('#J_data_price').text( '￥' + data.rmb.toFixed(2));
        })
    }
    getData()

    //搜索
    $('#J_search_btn').on('click', function() {
        var params = {
            account: $('#account').val().trim(),
            mobile: $('#mobile').val().trim(),
            promoter_id: $('#inviteCode').val().trim(),
            pay_time: $('#J_pay_time .J_search_timeStart').val().trim() + ' ' + $('#J_pay_time .J_search_timeEnd').val().trim(),
            auth_time: $('#J_auth_time .J_search_timeStart').val().trim() + ' ' + $('#J_auth_time .J_search_timeEnd').val().trim(),
            valid_time: $('#J_valid_time .J_search_timeStart').val().trim() + ' ' + $('#J_valid_time .J_search_timeEnd').val().trim(),
            page: g._page,
            size: g._size
        }
        getStream(params)
    })
})