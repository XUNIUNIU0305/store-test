$(function () {
    ;!function() {
        var timer;
        $('#apxModalAdminAlertReject').on('show.bs.modal', function() {
            var cont = 4;
            timer = setInterval(function() {
                cont--;
                $('#J_second').html(cont);
                if (cont === 0) {
                    alert(1)
                    clearInterval(timer)
                }
            }, 1000)
        }).on('hidden.bs.modal', function() {
            clearInterval(timer)
        })
    }()

    // 暂存全局数据
    var g = {
        current_record: 0,
        record_size: 20
    }

    function showMsg(msg) {
        $('#J_alert_content').html(msg);
        $('#apxModalAdminAlert').modal('show');
    }

    // 检测入金验证码是否存在
    ;!function() {
        var no = localStorage.getItem('ver_seq_no');  
        var time = localStorage.getItem('GET_NO_TIME');  
        var _now = new Date().getTime();
        if (time && time < (_now - 1000 * 60 * 60 * 2)) {
            localStorage.setItem('ver_seq_no', '')
        }
    }()

    // 出金
    $('#J_out_sure').on('click', function() {
        $('#apxModalAdminOutMoney').modal('hide');
        var rmb = $('#J_out_input').val().trim();
        var resultMB = rmb.search(/[^0-9.]/g);
        if (resultMB != -1) {
            showMsg('输入金额错误')
            return
        }
        $('#apxModalAdminLoading').modal({
            backdrop: 'static',
            keyboard: false
        })
        requestUrl('/nanjing/fund-management/draw', 'POST', {rmb: rmb}, function(data) {
            $('#apxModalAdminLoading').modal('hide')
            if (data.is_success) {
                $('#J_out_input').val('');
                showMsg('成功！')
                $('#apxModalAdminAlert').one('hidden.bs.modal', function() {
                    getBalance('1')
                })
            } else {
                showMsg(data.err_msg)
            }
        }, function(data) {
            $('#apxModalAdminLoading').modal('hide')
            alert(data.data.errMsg)
        })
    })

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
            startTimer();
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
                    getBalance('1')
                })
            } else {
                showMsg(data.err_msg)
            }
        }, function(data) {
            $('#apxModalAdminLoading').modal('hide')
            alert(data.data.errMsg)
        })
    })

    // 获取明细列表
    var tpl = $('#J_tpl_list').html();
    function trim(data) {
        return parseFloat(data).toFixed(2);
    }
    juicer.register('trim', trim);
    function getDetail(params, status) {
        var _data = {
            begin_date: null,
            end_date: null,
            current_record: 0,
            record_size: g.record_size
        }
        $.extend(_data, params);
        if (status == 1) {
            $('#J_list_box').html('');
            $('#J_show_more').addClass('hidden');
            $('#J_list_loading').removeClass('hidden');
        }
        if (status == 2) {
            var html = `<div class="modal-loading-box">
                    <div class="loading-inner">
                        <div class="line-spin-fade-loader">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                </div>`;
            $('#J_show_more').html(html)
        }
        requestUrl('/nanjing/fund-management/list', 'GET', _data, function(data) {
            $('#J_show_more').removeClass('hidden');
            $('#J_list_loading').addClass('hidden');
            if (data.is_success) {
                $('#J_show_more').html('点击加载更多').on('click.addMore');
                // status 为1时为查询 2为更多
                if (status == 1) {
                    $('#J_list_box').html(juicer(tpl, data.list));
                } else {
                    $('#J_list_box').append(juicer(tpl, data.list));
                }
            } else if (data.err_msg === '订单不存在') {
                $('#J_show_more').html('没有更多了').off('click.addMore');
            }
        })
    }
    // 查询
    $('#J_search_btn').on('click', function() {
        var start = $('.J_search_timeStart').val();
        var end = $('.J_search_timeEnd').val();
        getDetail({
            begin_date: start,
            end_date: end
        }, 1)
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

    // 获取更多
    $('#J_show_more').on('click.addMore', function() {
        var start = $('.J_search_timeStart').val();
        var end = $('.J_search_timeEnd').val();
        g.current_record = (g.current_record - 0) + (g.record_size - 0);
        getDetail({
            begin_date: start,
            end_date: end,
            current_record: g.current_record
        }, 2)
    })

    //重置
    $('#J_reset_out').on('click', function() {
        $('#J_out_input').val('')
    })
    $('#J_reset_add').on('click', function() {
        $('#J_add_input').val('')
        $('#J_captcha_input').val('')
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
