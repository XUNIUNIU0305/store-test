$(function () {
    // get sms
    var timerAll = [],
        intervalAll = [];
    var _smsData = {};

    function showMessage(msg) {
        $('#J_alert_content').html(msg);
        $('#apxModalAdminAlert').modal('show');
    }

    //获取当前用户手机号码
    function getUserMobile() {
        _smsData = {};
        function submitCB(data) {
            if (data.mobile == 0) {
                //new
                $('.form-box').removeClass('hidden')
                $('.new-mobile-btn').off('click').on('click',function () {
                    var mobile = $('.new-mobile-num').val();
                    var verify_code = $('.verify-new-code').val();
                    if (mobile.length === 0) {
                        showMessage('请填写手机号码')
                        return
                    }
                    var resultMB = mobile.search(/0?(1)[0-9]{10}/);
                    if (resultMB === -1) {
                        showMessage('请填写正确的手机号码')
                        return
                    }
                    if(verify_code === '') {
                        showMessage('请填写短信验证码')
                        return
                    }  
                    var _data = {
                        "mobile":mobile,
                        "verify_code":verify_code
                    }
                    function submitCB () {
                        $('.bind-status').find('.pull-right').addClass('done')
                        $('.form-container').addClass('hidden')
                        $('.bind-success').removeClass('hidden')
                        $('.change_new_ipt').removeClass('hidden')
                        $('.J_get_verify_sms').removeClass('disabled').text('获取验证码')
                        interval && clearInterval(interval);
                    }
                    function errCB(data) {
                        showMessage(data.data.errMsg);
                        return false;
                    }
                    requestUrl('/account/mobile/bindmobile', 'POST', _data, submitCB, errCB);
                } )
            } else {
                //new
                $('.bind-over').removeClass('hidden')
                $('#J_mobile').text(data.mobile)
                $('.edit').off('click').on('click',function () {
                    $('.bind-over').addClass('hidden')
                    $('.change-bind').removeClass('hidden')
                    $('.num').text(data.mobile)
                    $('.top-title').text('修改绑定手机')
                    $('.prompt-msg2').removeClass('hidden')
                })
                //已绑定手机验证
                verifyOldMobile();
            }

        }

        function errCB(data) {
            alert(data.data.errMsg);
            return;
        }

        requestUrl('/account/mobile/getmobile', 'POST', '', submitCB, errCB);

    }
    //new
    //验证已绑定手机号码
    function verifyOldMobile () {
        $('#change_mobile_btn').off('click.newMobile').on('click.change',function () {
            var verifyCode = $(".change_verify_ipt").val();
            if (verifyCode.length == 0) {
                showMessage("请填写手机验证码")
                return false
            }
            var _data = {verify_code: verifyCode}
            function submitCB(data) {
                //进入下一步
                $('.change-status').find('span').eq(1).addClass('done')
                $('.change-mobile').removeClass('hidden')
                $('.num').addClass('hidden')
                $(".change_verify_ipt").val('').addClass('hidden')
                $('.change_new_ipt').removeClass('hidden')
                $('.J_get_verify_sms').removeClass('disabled').text('获取验证码')
                interval && clearInterval(interval);
                //绑定新手机事件
                bindNewMobileEvents()
            }
            function errCB(data) {
                showMessage(data.data.errMsg)
                return
            }
            requestUrl('/account/mobile/verifysms', 'POST', _data, submitCB, errCB)
        })
    }
 
    //更改手机号码事件
    function bindNewMobileEvents() {
        //new
        $('#change_mobile_btn').off('click').on('click',function () {
            var oldVerifyCode = '';
            var newVerifyCode = $(".change_new_ipt").val();
            var newMobile = $(".change-mobile").val(); 
            if (newMobile.length == 0) {
                showMessage("请填写新手机号码")
                return
            }
            var resultMB = newMobile.search(/0?(1)[0-9]{10}/);
            if (resultMB == -1) {
                showMessage("请填写正确的手机号码");
                return
            }
            if (newVerifyCode.length == 0) {
                showMessage("请填写短信验证码")
                return
            }
            var _data = {
                "old_verify_code": oldVerifyCode,
                'verify_code': newVerifyCode,
                'mobile': newMobile,
            };

            function submitCB(data) {
                $('.form-container').addClass('hidden')
                $('.change-status').find('span').eq(2).addClass('done')
                $('.bind-success').removeClass('hidden')
            }

            function errCB(data) {
                showMessage(data.data.errMsg);
                return;
            }
            requestUrl("/account/mobile/changemobile", 'POST', _data, submitCB, errCB);
        })
    }

    //发送验证码
    $(".J_get_verify_sms").off("click").on("click", function () {
        var url = "/account/mobile/sendsms";
        var index = $(".J_get_verify_sms").index(this);
        //第二次发送，绑定新手机号码
        if (index === 1) {
            if (!$('.change_new_ipt').hasClass('hidden')) {
                var newMobile = $(".change-mobile").val();
                if (newMobile.length == 0) {
                    showMessage("请填写手机号码");
                    return;
                }
                var resultMB = newMobile.search(/0?(1)[0-9]{10}/);
                if (resultMB == -1) {
                    showMessage("请填写正确的手机号码1");
                    return
                }
                url = "/sms/send";
                _smsData = {"mobile": newMobile,type:2};
            }
        } else {
            var newMobile = $(".new-mobile-num").val();
            if (newMobile.length == 0) {
                showMessage("请填写新手机号码");
                return;
            }
            var resultMB = newMobile.search(/0?(1)[0-9]{10}/);
            if (resultMB == -1) {
                showMessage("请填写正确的手机号码");
                return
            }
            url = "/sms/send";
            _smsData = {"mobile": newMobile, type: 2};
        }
        var $that = $(this);
        function submitCB(data) {
            startTimer($that);
        }

        //错误返回
        function errCB(data) {
            $('.J_get_verify_sms').removeClass('disabled');
            alert(data.data.errMsg)
        }
        $that.addClass('disabled');
        requestUrl(url, 'GET', _smsData, submitCB, errCB)
    });

    var timer, interval;
    //启用计时启
    function startTimer($that) {
        var $this = $that;
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
    //加载手机号码
    getUserMobile();

})
