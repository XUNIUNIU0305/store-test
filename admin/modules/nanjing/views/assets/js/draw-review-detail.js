$(function() {
	$('#collapseExample').on('hidden.bs.collapse', function () {
        $('.go-pay').removeClass('active')
        $('#go_pay').html('去充值')
    }).on('show.bs.collapse', function() {
        $('.go-pay').addClass('active')
        $('#go_pay').html('取消充值')
    })

    function showMsg(msg) {
        $('#J_alert_content').html(msg);
        $('#apxModalAdminAlert').modal('show');
    }

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
    // $('#apxModalAdminAlertReject').modal('show');
    // 获取详情
    function getDetail(id) {
        requestUrl('/nanjing/draw-review-detail/detail', 'GET', {draw_id: id}, function(data) {
            var user_type = '';
            if (data.account.user_type == '1') {
                user_type = 'CUSTOM'
            }
            if (data.account.user_type == '2') {
                user_type = 'SUPPLY'
            }
            if (data.account.user_type == '3') {
                user_type = 'BUSINESS'
            }
            if (data.account.user_type == '4') {
                user_type = 'ADMIN'
            }
            $('#J_user_info_type').html(user_type);
            $('#J_user_account').html(data.account.user_account);
            $('#J_user_mobile').html(data.account.user_phone);

            $('#J_bank_name').html(data.bank.bank_name);
            $('#J_user_name').html(data.bank.acct_name);
            $('#J_bank_code').html(data.bank.acct_no);
            var acct_type = '';
            if (data.bank.acct_type === 0) {
                acct_type = '企业账户'
            }
            if (data.bank.acct_type === 1) {
                acct_type = '个人账户'
            }
            $('#J_bank_type').html(acct_type);
            $('#J_bank_mobile').html(data.bank.acct_phone);
            $('#J_create_time').html(data.bank.create_time);

            var apply_status = '';
            if (data.status == 0) {
                apply_status = '未审核'
                $('#J_handle_box').removeClass('hidden');
            }
            if (data.status == 1) {
                apply_status = '通过'
            }
            if (data.status == 2) {
                apply_status = '驳回'
            }
            if (data.status == 3) {
                apply_status = '失败'
            }
            if (data.status == 4) {
                apply_status = '成功'
            }
            $('#J_apply_count').html(data.rmb);
            $('#J_apply_status').html(apply_status);
            $('#J_apply_time').html(data.apply_time);
            $('#J_pass_time').html(data.pass_time);
            $('#J_reject_time').html(data.reject_time);
            $('#J_failure_time').html(data.failure_time);
            $('#J_success_time').html(data.success_time);
            $('#J_verify_msg').html(data.verify_msg);
        })
    }
    getDetail(url('?id'))

    // 返回上级
    $('#J_go_back').on('click', function() {
        window.location.href = '/nanjing/draw-review-list?id=' + url('?id') + '&page=' + url('?page');
    })

    // 通过
    $('#J_pass_sure').on('click', function() {
        $('#apxModalAdminPass').modal('hide');
        var id = url('?id');
        $('#apxModalAdminLoading').modal({
            backdrop: 'static',
            keyboard: false
        })
        requestUrl('/nanjing/draw-review-detail/pass', 'POST', {draw_id: id}, function(data) {
            if (data.is_success) {
                setTimeout(function() {
                    $('#apxModalAdminLoading').modal('hide');
                    showMsg('成功！') 
                    $('#apxModalAdminAlert').one('hidden.bs.modal', function() {
                        window.location.href = '/nanjing/draw-review-list';
                    })
                }, 3000)
            } else {
               $('#apxModalAdminLoading').modal('hide'); 
               showMsg(data.err_msg)
            }
        }, function(data) {
            $('#apxModalAdminLoading').modal('hide'); 
            showMsg(data.data.errMsg)
        })
    })
    // 驳回
    $('#J_reject_sure').on('click', function() {
        $('#apxModalAdminRejectRemark').modal('hide');
        var msg = $('#J_reject_remark').val();
        if (msg.length < 1) {
            showMsg('留言必填');
            return
        }
        var _data = {
            draw_id: url('?id'),
            msg: msg
        }
        $('#apxModalAdminLoading').modal({
            backdrop: 'static',
            keyboard: false
        })
        requestUrl('/nanjing/draw-review-detail/reject', 'POST', _data, function(data) {
            $('#apxModalAdminLoading').modal('hide');
            showMsg('成功！') 
            $('#apxModalAdminAlert').one('hidden.bs.modal', function() {
                window.location.href = '/nanjing/draw-review-list';
            })
        }, function(data) {
            $('#apxModalAdminLoading').modal('hide');
            showMsg(data.data.errMsg)
        })
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
                    getBalance('1')
                    $('#go_pay').click()
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