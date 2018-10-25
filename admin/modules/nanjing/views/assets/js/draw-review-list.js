$(function() {
    
    function showMsg(msg) {
        $('#J_alert_content').html(msg);
        $('#apxModalAdminAlert').modal('show');
    }
    
    $('#collapseExample').on('hidden.bs.collapse', function () {
        $('.go-pay').removeClass('active')
        $('#go_pay').html('去充值')
    }).on('show.bs.collapse', function() {
        $('.go-pay').addClass('active')
        $('#go_pay').html('取消充值')
    })
    // 获取提现列表
    var tpl = $('#J_tpl_list').html();
    function getList(params) {
        var _data = {
            current_page: 1,
            page_size: 20,
            status: 0
        }
        $.extend(_data, params);
        requestUrl('/nanjing/draw-review-list/list', 'GET', _data, function(data) {
            $('#J_list_box').html(juicer(tpl, data.list));
            pagingBuilder.build($('#J_page_list'), _data.current_page, _data.page_size, data.total_count);
            pagingBuilder.click($('#J_page_list'), function(page) {
                getList($.extend(params, {current_page: page}))
            })
            if (url('?id')) {
                $('#J_list_box tr[data-id="' + url('?id') + '"]').addClass('active')
            }
        })
    }
    getList({
        current_page: url('?page') || 1
    })
    // 切换状态
    $('#J_tabs_list').on('click', '.btn', function() {
        $(this).addClass('active').siblings('.btn').removeClass('active');
        var status = $(this).data('status');
        getList({status: status})
    })

    $('#J_list_box').on('click', 'tr', function() {
        var id = $(this).data('id');
        var page = $('#J_page_list li.active').data('page');
        window.location.href = '/nanjing/draw-review-detail?id=' + id + '&page=' + page;
    })

    // 获取余额
    function getBalance(force) {
        var _data = {
            force: force || 0
        }
        requestUrl('/nanjing/fund-management/balance', 'GET', _data, function(data) {
            $('#J_balance').html('￥' + parseFloat(data.rmb).toFixed(2))
        })
    }
    getBalance()

    // 检测入金验证码是否存在
    ;!function() {
        var no = localStorage.getItem('ver_seq_no');  
        var time = localStorage.getItem('GET_NO_TIME');  
        var _now = new Date().getTime();
        if (time && time < (_now - 1000 * 60 * 60 * 2)) {
            localStorage.setItem('ver_seq_no', '')
        }
    }()
    
    // 入金验证码
    $('#J_captcha_btn').on('click', function(e) {
        var $that = $(this);
        var timerAll = [],
        intervalAll = [];
        //启用计时启
        function startTimer() {
            var timer, interval;
            e.preventDefault();
            var $this =$that;
            var countDown = 60;
            // disable it
            //if ($this.hasClass('disabled')) return;
            $this.addClass('disabled');
            // revert changes after 60s
            timer = setTimeout(function () {
                $this.text('点击获取');
                $this.removeClass('disabled');
                interval && clearInterval(interval);
            }, 60 * 1000);
            timerAll.push(timer);
            // set count down text
            $this.text(countDown + '秒后重试');
            interval = setInterval(function () {
                countDown--;
                $this.text(countDown + '秒后重试');
            }, 1000);
            intervalAll.push(interval);
        }
        requestUrl('/nanjing/fund-management/send-captcha', 'POST', '' , function(data) {
            startTimer()
            if (data.is_success) {
                localStorage.setItem('ver_seq_no', data.ver_seq_no);
                var _date = new Date().getTime();
                localStorage.setItem('GET_NO_TIME', _date);
            } else {
                showMsg(data.err_msg);
            }
        })
    })
    // 入金
    $('#J_add_sure').on('click', function() {
        $('#apxModalAdminAddMoney').modal('hide');
        var rmb = $('#J_add_input').val().trim();
        var captcha = $('#J_captcha_input').val().trim();
        var no = localStorage.getItem('ver_seq_no');
        if (no.length < 1) {
            showMsg('请重新获取验证码！')
            return
        }
        var resultMB = rmb.search(/[^0-9.]/g);
        if (resultMB != -1) {
            showMsg('输入金额错误')
            return
        }
        if (captcha.length < 1) {
            showMsg('请输入验证码！')
            return
        }
        $('#apxModalAdminLoading').modal({
            backdrop: 'static',
            keyboard: false
        })
        requestUrl('/nanjing/fund-management/deposit', 'POST', {rmb: rmb, captcha: captcha, ver_seq_no: no}, function(data) {
            $('#apxModalAdminLoading').modal('hide');
            if (data.is_success) {
                $('#J_add_input').val('');
                $('#J_captcha_input').val('');
                showMsg('成功！')
                $('#apxModalAdminAlert').one('hidden.bs.modal', function() {
                    getBalance('1');
                    $('#go_pay').click();
                })
            } else {
                showMsg(data.err_msg)
            }
        }, function(data) {
            $('#apxModalAdminLoading').modal('hide')
            alert(data.data.errMsg)
        })
    })
    // 获取用户余额
    function getUserBalance() {
        requestUrl('/nanjing/fund-management/all-users-fund', 'GET', '', function(data) {
            $('#J_user_balance').html(parseFloat(data[3].balance).toFixed(2));
            $('#J_frost_money').html(parseFloat(data[3].frozen).toFixed(2));
            $('#J_update_time').html(data.time);
        })
    }
    getUserBalance()
})