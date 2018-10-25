$(function() {
    // 输入框效果
    $('.form-group').on('focus', 'input',function() {
        $(this).parents('.form-group').addClass('focus active')
    }).on('blur', 'input', function() {
        $(this).parents('.form-group').removeClass('focus')
        if ($(this).val() === '') {
            $(this).parents('.form-group').removeClass('active')
        }
    })

    // 车主查询
    var owner = {
        getSms: function(mobile) {
            requestUrl('/quality/quality-search/send-mobile-captcha', 'GET', {
                owner_mobile: mobile
            }, function(data) {
                startTimer($('#J_get_captcha'))
            })
        },
        clickHandle: function() {
            var mobile = $('#mobile').val();
            owner.getSms(mobile)
        },
        searchInfo: function() {
            $('#home .form-group').removeClass('error')
            var no = $('#carCode').val().toString().trim();
            var mobile = $('#mobile').val().toString().trim();
            var captcha = $('#captcha').val().toString().trim();
            if (no.length < 6 || no.length > 17) {
                $('#home .form-group').eq(0).addClass('error')
                return
            }
            if (mobile.search(/^1[0-9]{10}$/) === -1) {
                $('#home .form-group').eq(1).addClass('error')
                return
            }
            if (captcha.length < 1) {
                $('#home .form-group').eq(2).addClass('error')
                return
            }
            params = {
                number_or_frame_of_car: no,
                owner_mobile: mobile,
                mobile_captcha: captcha
            }
            requestUrl('/quality/quality-search/auth-by-owner', 'POST', params, function(data) {
                if (data.order_code) {
                    location.href = '/quality/quality-search/owner-detail?order_code=' + data.order_code
                    return
                }
                location.href = '/quality/quality-search/owner-list'
            }, function(data) {
                if (data.status === 3364) {
                    $('#home .form-group').eq(2).addClass('error')
                    return
                }
                if (data.status === 3406) {
                    $('#home .form-group').eq(1).addClass('error')
                    return
                }
                if (data.status === 3403) {
                    $('#home .form-group').eq(0).addClass('error')
                    return
                }
                alert(data.data.errMsg)
            })
        },
        init: function() {
            $('#J_get_captcha').on('click', owner.clickHandle)
            $('#J_owner_search').on('click', owner.searchInfo)
            // 输入框正则
            $('#carCode').on('keyup', function() {
                var val = $(this).val().toString().trim();
                var newVal = val.replace(/[^0-9a-zA-Z\u4e00-\u9fa5]/,'')
                $(this).val(newVal)
            }).on('blur', function() {
                $(this).val($(this).val().toUpperCase())
            })
            $('#mobile').on('keyup', function() {
                var val = $(this).val().toString().trim();
                var newVal = val.replace(/[^0-9]/g,'');
                $(this).val(newVal);
            })
        }
    }
    owner.init()

    // 服务商（门店）查询
    var custom = {
        clickHandle: function(params) {
            requestUrl('/quality/quality-search/auth-by-custom', 'POST', params, function(data) {
                location.href = '/quality/quality-search/custom-search'
            }, function(data) {
                getCaptchaImg()
                alert(data.data.errMsg)
            })
        },
        init: function() {
            $('#J_login_now').on('click', function() {
                $('#service .form-group').removeClass('error')
                var account = $('#quality_account').val();
                var pwd = $('#J_login_pwd').val();
                var captcha = $('#captcha2').val();
                if (account.length < 1 || account.length > 11) {
                    $('#service .form-group').eq(0).addClass('error')
                    return
                }
                if (captcha.length < 1) {
                    $('#service .form-group').eq(2).addClass('error')
                    return
                }
                custom.clickHandle({
                    account_or_mobile_of_custom: account,
                    password: pwd,
                    captcha: captcha
                })
            })
            getCaptchaImg();

            // 输入框验证
            $('#quality_account').on('keyup', function() {
                var val = $(this).val().toString().trim();
                var newVal = val.replace(/[^0-9]/g,'');
                $(this).val(newVal);
            })
        }
    }
    custom.init()

    // 验证码 与 刷新
    function getCaptchaImg() {
        $.ajax({
                url: "/captcha",
                method: "GET",
                data: {
                    'refresh': Math.random() + ''
                }
            })
            .done(function(data) {
                // append image node
                $('.J_verify_captcha').empty()
                    .css({
                        'padding': 0,
                        'background': 'url('+data.url+') no-repeat center'
                    });
                    // set style and event listener
                $('.J_verify_captcha').one('click', getCaptchaImg);
            })
            .fail(function() {
                setTimeout(function() {
                    getCaptchaImg();
                }, 3000);
            })
    }

    //启用计时启
    var timerAll = [],
        intervalAll = [];
    function startTimer($that){
        var timer, interval;
        // e.preventDefault();
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
})