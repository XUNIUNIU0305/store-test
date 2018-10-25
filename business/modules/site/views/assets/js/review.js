$(function() {
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
    $('input.J_search_timeEnd').val(nowTime);
    $('.J_timeStart_show').on('click', function() {
        $(this).parents('.date-box').find('.J_search_timeStart').datepicker('show')
    })
    $('.J_timeEnd_show').on('click', function() {
        $(this).parents('.date-box').find('.J_search_timeEnd').datepicker('show')
    })

    //初始化展示页面
    var status = url('?status')
    $('.nav-tabs li').eq(status).find('a').click()

    //获取列表
    var g = {
    	page: 1,
    	size: 10,
    	tpl1: $('#J_tpl_list1').html(),
    	tpl2: $('#J_tpl_list2').html(),
    	pageBox1: $('#J_success_page'),
    	pageBox2: $('#J_auth_page'),
    	pageBox3: $('#J_valid_page')
    }
    function getList(params, status) {
    	var _data = {
    		account: params.account,
    		mobile: params.mobile,
    		pay_time: params.pay_time,
    		auth_time: params.auth_time,
    		valid_time: params.valid_time,
    		current_page: params.current_page,
    		page_size: params.page_size,
    		status: params.status
    	}
    	requestUrl('/site/promoter/stream-log', 'GET', _data, function(data) {
    		if (status === 1) {
    			$('#J_list_success').html(juicer(g.tpl2, data));
    			//生成分页
	            if (data.data.length > 1) {
	                pagingBuilder.build(g.pageBox1, params.current_page, params.page_size, data.total_count);
	                pagingBuilder.click(g.pageBox1, function(page) {
	                	params.current_page = page;
	                    getList(params, status);
	                })
	            }
    		}
    		if (status === 2) {
    			$('#J_list_auth').html(juicer(g.tpl1, data));
    			//生成分页
	            if (data.data.length > 1) {
	                pagingBuilder.build(g.pageBox2, params.current_page, params.page_size, data.total_count);
	                pagingBuilder.click(g.pageBox2, function(page) {
	                	params.current_page = page;
	                    getList(params, status);
	                })
	            }
    		}
    		if (status === 3) {
    			$('#J_list_valid').html(juicer(g.tpl1, data));
    			//生成分页
	            if (data.data.length > 1) {
	                pagingBuilder.build(g.pageBox3, params.current_page, params.page_size, data.total_count);
	                pagingBuilder.click(g.pageBox3, function(page) {
	                	params.current_page = page;
	                    getList(params, status);
	                })
	            }
    		}
    	})
    }
    getList({
    	current_page: g.page,
    	page_size: g.size,
    	status: '5'
    }, 1)
    getList({
    	current_page: g.page,
    	page_size: g.size,
    	status: '2'
    }, 2)
    getList({
    	current_page: g.page,
    	page_size: g.size,
    	status: '1,3'
    }, 3)

    //搜索
    //注册成功
    $('#J_success_btn').on('click', function() {
    	getList({
    		account: $('#J_success_account').val().trim(),
    		mobile: $('#J_success_mobile').val().trim(),
    		pay_time: $('#J_success_pay .J_search_timeStart').val() + ' ' + $('#J_success_pay .J_search_timeEnd').val(),
    		auth_time: $('#J_success_auth .J_search_timeStart').val() + ' ' + $('#J_success_auth .J_search_timeEnd').val(),
    		valid_time: $('#J_success_valid .J_search_timeStart').val() + ' ' + $('#J_success_valid .J_search_timeEnd').val(),
    		current_page: g.page,
    		page_size: g.size,
    		status: 5
    	}, 1)
    })
    //待审核
    $('#J_auth_btn').on('click', function() {
    	getList({
    		account: $('#J_auth_account').val().trim(),
    		mobile: $('#J_auth_mobile').val().trim(),
    		pay_time: $('#J_auth_pay .J_search_timeStart').val() + ' ' + $('#J_auth_pay .J_search_timeEnd').val(),
    		current_page: g.page,
    		page_size: g.size,
    		status: 2
    	}, 2)
    })
    //待提交
    $('#J_valid_btn').on('click', function() {
    	getList({
    		account: $('#J_valid_account').val().trim(),
    		mobile: $('#J_valid_mobile').val().trim(),
    		pay_time: $('#J_valid_pay .J_search_timeStart').val() + ' ' + $('#J_valid_pay .J_search_timeEnd').val(),
    		current_page: g.page,
    		page_size: g.size,
    		status: '1,3'
    	}, 3)
    })
})