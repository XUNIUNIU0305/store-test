$(function() {
    //日期验证
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
    
    var cancel = {
        tpl: $('#J_tpl_list').html(),
        getList: function(params) {
            var _default = {
                current_page: 1,
                page_size: 20
            }
            $.extend(_default, params)
            requestUrl('/account/gpubs-picking-up/detail-list', 'GET', _default, function(data) {
                $('#J_list_container').html(juicer(cancel.tpl, data.data))
                if (data.data.length > 0) {
                    pagingBuilder.build($('#J_page_box'), _default.current_page, _default.page_size, data.total_count)
                    pagingBuilder.click($('#J_page_box'), function(page) {
                        _default.current_page = page
                        cancel.getList(_default)
                    })
                } else {
                    $('#J_page_box').html('')
                }
            })
        },
        getPickPro: function(params) {
            requestUrl('/account/gpubs-picking-up/detail-info', 'GET', params, function(data) {
                if (data.quantity) {
                    $('#apxModalAdminAlertReject').modal('show')
                } else {
                    alert('未查到可提货订单！')
                    return
                }
                $('#J_pick_img').attr('src', data.product_image)
                $('#J_pick_title').text(data.product_title)
                $('#J_order_total').text(data.quantity)
                $('#J_order_balance').text(data.quantity - data.picked_up_quantity)
                var attr = ''
                $.each(data.sku_attributes, function(i, val) {
                    attr += val.name + ':' + val.selectedOption.name + '；'
                })
                $('#J_pick_attr').text(attr)
            })  
        },
        pickUp: function(params) {
            requestUrl('/account/gpubs-picking-up/pick-up', 'POST', params, function(data) {
                alert('提货成功！')
                $('#apxModalAdminAlertReject').modal('hide')
                $('#J_search_btn').click()
            })  
        },
        init: function() {
            this.getList()
            // 提货
            $('#J_pick_up').on('click', function() {
                var no = $('#J_pick_up_input').val().trim();
                if (no === '') {
                    return false
                }
                cancel.getPickPro({
                    picking_up_number: no
                })
            })
            // 加数量
            $('#J_add_num').on('click', function() {
                var val = $('#J_pick_num_input').val()
                val++
                $('#J_pick_num_input').val(val)
            })
            // 减数量
            $('#J_minus_num').on('click', function() {
                var val = $('#J_pick_num_input').val()
                if (val > 1) {
                    val--
                    $('#J_pick_num_input').val(val)
                }
            })
            // 确定提货
            $('#J_pick_up_btn').on('click', function() {
                cancel.pickUp({
                    picking_up_number: $('#J_pick_up_input').val(),
                    quantity: $('#J_pick_num_input').val()
                })
            })
            // 搜索
            $('#J_search_btn').on('click', function() {
                var status = $('#J_order_status').val()
                var no = $('#J_order_no').val()
                var timestart = $('.J_search_timeStart').val()
                var timesend = $('.J_search_timeEnd').val()
                var account = $('#J_account_no').val()
                cancel.getList({
                    status: status,
                    group_number: no,
                    account: account,
                    pick_start_date: timestart,
                    pick_end_date: timesend
                })
            })
        }
    }
    cancel.init()
    juicer.register('status', function(data) {
        var status = {
            0: '拼购过期',
            1: '拼购中',
            2: '未提货',
            3: '部分提货',
            4: '全部提货',
            6: '参团失败'
        }
        return status[data]
    })
})