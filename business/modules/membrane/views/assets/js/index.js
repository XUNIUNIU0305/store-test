(function () {
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
    // $('input.J_search_timeEn').val(nowTime);
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

    requestUrl('/membrane/home/status', 'get', {}, function (status) {

        var selectHtml = '<option value="">全部状态</option>';
        $.each(status, function (key, val) {
            selectHtml += '<option value="' + key + '">' + val + '</option>'
        })
        document.getElementById('status-select').innerHTML = selectHtml;

        var params = { page: 1, page_size: 10};

        function search() {
            requestUrl('/membrane/home/search', 'get', params, function (data) {
                data.status = status;
                itemBox.innerHTML = juicer(itemTpl, data)
                pagingBuilder.build($page, data.page, data.page_size, data.count);
                pagingBuilder.click($page, function (page) {
                    params.page = page
                    search()
                })
                $(document).scrollTop('0');
            })
        }

        var $page = $('#page')

        var itemTpl = document.getElementById('item').innerHTML
        var itemBox = document.getElementById('item-box')

        var $confirm = $('#confirm');

        $(document).on('click', '.accept-btn', function () {
            $confirm.modal('show')
            $confirm.data({no: this.getAttribute('data-id'), method: 'accept'})
        }).on('click', '.finish-btn', function () {
            $confirm.modal('show')
            $confirm.data({no: this.getAttribute('data-id'), method: 'finish'})
        })
        $('#confirm-success').on('click', function () {
            $confirm.modal('hide')
            var data = $confirm.data()
            requestUrl('/membrane/home/' + data.method, 'post', {order_number: data.no}, function () {
                search()
            })
        })
        search()

        var $form = $('#search').submit(function (e) {
            e.preventDefault()
            var paramsString = $form.serializeArray()
            $.each(paramsString, function (i, field) {
                params[field.name] = field.value
            })
            params.page = 1;
            search()
        })
        $(document).on('click', '.J_cancel_btn', function() {
            var yes = confirm('确定取消订单？');
            if (!yes) return;
            var no = $(this).data('id');
            requestUrl('/membrane/home/cancel', 'POST', {no: no}, function(data) {
                search()
            })
        })
    })
}())
