$(function(){
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

    var params = { page: 1, page_size: 10, status: 1};

    var pageListBox = $('#cus_page_list');
    var tableBox = $("#tableBox");
    var tpl_order= document.getElementById('tpl_order').innerHTML

    function search(){
        requestUrl("/account/membrane/search", 'GET', params, function(data){
           //获取订单信息列表，渲染数据到页面 
            tableBox.html(juicer(tpl_order, data));
            //分页功能
            var pageListBox = $('#cus_page_list');
            pagingBuilder.build(pageListBox, params.page, params.page_size, data.count);
            pagingBuilder.click(pageListBox, function(page) {
            	params.page = page
                search(params);
            });   
        })
    }
    var inputs = $(".order-msg li").find("input")
    // var select = $('.order-msg li select')
    $('#J_search_btn').on('click', function(){
        params.page = 1;
        for(var i=0;i<inputs.length;i++){
            var input = inputs[i];
            params[input.name] = input.value                
        }
        params.status = $('#J_tab_list li[class*="active"]').data('id')
        search()
    })

    search()

    //点击付款页面跳转
    tableBox.on('click', '.J_to_pay', function () {
        var no = this.getAttribute('data-no');
        var $this = $(this);
        var payment = $this.parent('.input-group-btn').siblings('.J_payment_box').find('input:checked').val()
        requestUrl('/account/membrane/pay', 'get', { method: payment, no: no }, function (data) {
            location.href = data.url
        })
    })

    // 状态切换
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var $this = $(e.target).parent('li');
        var status = $this.data('id');
        $('.order-select input').val('');
        $('#J_search_btn').click();
    })

    // 取消订单
    $('#tableBox').on('click', '.J_cancel_order', function() {
        var yes = confirm('确定要取消订单吗？');
        if (!yes) return;
        var no = $(this).data('no');
        requestUrl(' /account/membrane/cancel', 'POST', {no: no}, function(data) {
            $('#J_search_btn').click();
        })
    })
})