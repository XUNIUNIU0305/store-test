

$(function () {
    var nowDate = new Date();
    var month = nowDate.getMonth() + 1;
    var day = nowDate.getDate();
    if (month.toString().length == 1) {
        month = '0' + month
    }
    if (day.toString().length == 1) {
        day = '0' + day
    }
    var nowTime = nowDate.getFullYear() + '-' + month + '-' + day;
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
        language: 'zh-CN'
    });


    $('.J_date_btn').on('click', function() {
        $(this).siblings('input').focus()
    })

    var scrolls = [];
    $('.iscroll_container').each(function () {
        scrolls.push(new IScroll(this, {
            mouseWheel: true,
            scrollbars: true,
            scrollbars: 'custom',
            preventDefault: false
        }))
    })
    function refreshScroll() {
        setTimeout(function() {
            scrolls.forEach(function (scroll) {
                scroll.refresh();
            })
        }, 300)
    }
    //查询方式切换
    $('#select_query').on('change', function() {
        var val = $(this).val();
        $('#J_search_input').val('');
        if (val == 3) {
            $('.query_time').addClass('in');
            $('.query_common').removeClass('in');
        } else {
            $('.query_common').addClass('in');
            $('.query_time').removeClass('in');
        }
    })
    var tpl = $('#J_tpl_list').html();
    //获取质保列表
    function getList(page, size, type, keyword, start, end) {
        var data = {
            current_page: page,
            page_size: size,
            type: type,
            keyword: keyword,
            start_time: start,
            end_time: end
        }
        requestUrl('/quality/qualityorder/search', 'GET', data, function(data) {
            var html = juicer(tpl, data);
            $('.J_user_list').html(html);
            //分页
            var pages = getPagination(page, Math.ceil(data.total_count/size));
            $('.J_user_page').html(pages);
            $('#J_page_box li').on('click', function() {
                var val = $(this).data('page');
                if (val == undefined) {
                    return false
                }
                getList(val, size, type, keyword, start, end)
            })
            $('#J_page_search input').on('keyup', function() {
                var number = $(this).val().replace(/\D/g,'') - 0;
                $(this).val(number);
                if ($(this).val().length < 1) {
                    $(this).val('1');
                    return false
                }
                if ($(this).val() < 1) {
                    $(this).val('1');
                    return false
                }
                if ($(this).val() > $('#J_page_box').data('max')) {
                    $(this).val($('#J_page_box').data('max'))
                    return false
                }
            })
            $('#J_page_search a').on('click', function() {
                var n = $('#J_page_search input').val();
                if (n > $('#J_page_box').data('max')) {
                    alert('已超过最大分页数')
                    return false;
                }
                getList(n, size, type, keyword, start, end)
            })
            refreshScroll()
        })
    }
    getList(1, 20, 0);
    //查询
    $('.J_search_btn').on('click', function() {
        var type = $('#select_query').val();
        $('#J_search_box').data('type', type);
        if (type == 3) {
            getList(1, 20, 3, '', $('.J_search_timeStart').val(), $('.J_search_timeEnd').val());
        } else {
            getList(1, 20, type, $('#J_search_input').val().trim())
        }
    })
    $('#J_search_input').on('keydown', function(e) {
        if (e.keyCode == 13) {
            $(this).siblings('.J_search_btn').click()
        }
    })





})