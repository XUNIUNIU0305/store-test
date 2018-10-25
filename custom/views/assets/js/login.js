$(function() {
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
    if ($('.J_verify_captcha').length > 0) getCaptchaImg();

    // 登陆
    function login() {
        $('.J_login_btn').addClass('disabled').off('.login');
        var tip = {
                account: $('#username').val(),
                passwd: $('#password').val(),
                captcha: $('#captcha').val()
            },
            info = [
                $('#username').val(),
                $('#password').val(),
                $('#captcha').val()
            ];

        // 空值判断
        $.each(info, function(key, val) {
            if (/^\s*$/.test(val)) {
                $('.error-container').eq(key).removeClass('hidden')
                $('.J_login_btn').removeClass('disabled').on('click.login', login)
                throw tip[key] + '不能为空'
                return false
            }
        });

        function loginCB(data) {
            $('.J_login_btn').removeClass('disabled').on('click.login', login)
            toggleErrorMsg();
            location.href = data.url;
        }
        function errCB(data) {
            $('.J_login_btn').removeClass('disabled').on('click.login', login)
            toggleErrorMsg(data.data.errMsg, true, getCaptchaImg);
        }
        requestUrl('/login/login', 'POST', tip, loginCB, errCB);
    }
    $('.J_login_btn').on('click.login', login)
    
    function toggleErrorMsg(msg, show, cb) {
        var $msg = $('.J_error_msg')
        if (!!show) $msg.html('<i class="glyphicon glyphicon-remove"></i> ' + msg).removeClass('hidden');
        else $msg.addClass('hidden');
        if (cb !== undefined) cb();
    }

    //独立验证验证码
    function captcha() {
        var data = {
            captcha: $('#validate').val()
        }
        function captchaCB(data) {
            if (data.result != true) {
                toggleErrorMsg('验证码错误！',true)
            }
            $('.captcha-img').addClass('img-captcha')
        }
        requestUrl('/login/verify-captcha', 'POST', data, captchaCB)
    }
    $('#validate').on('blur', function() {
        captcha()
    })

    $('input').on('focus', function() {
        $('.error-container').addClass('hidden')
        $('.J_error_msg').addClass('hidden')
    })

    //回车登录
    $('input').on('keydown', function(e) {
        var ev = document.all?window.event:e;
        if (ev.keyCode == 13) {
            login()
        }
    })

    //获取微信登录url
    requestUrl('/wechat/login-url', 'GET', '', function(data) {
        $('#J_wechat_login').attr('href', data.url)
    })


})
