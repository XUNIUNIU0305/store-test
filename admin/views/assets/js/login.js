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
                account: '用户名',
                passwd: '密码',
                captcha: '验证码'
            },
            info = {
                account: $('#username').val(),
                passwd: $('#password').val(),
                captcha: $('#validate').val()
            };

        // 空值判断
        $.each(info, function(key, val) {
            if (!val) {
                toggleErrorMsg(tip[key] + '不能为空!', true)
                $('.J_login_btn').removeClass('disabled').on('click.login', login)
                throw tip[key] + '不能为空'
            }
        });

        function loginCB(data) {
            $('.J_login_btn').removeClass('disabled').on('click.login', login);
            toggleErrorMsg();
            location.href = data.url;
        }
        function errCB(data) {
            $('.J_login_btn').removeClass('disabled').on('click.login', login);
            toggleErrorMsg(data.data.errMsg, true, getCaptchaImg);
        }
        requestUrl('/index/login', 'POST', info, loginCB, errCB, false);
    }
    $('.J_login_btn').on('click.login', login)
    
    function toggleErrorMsg(msg, show, cb) {
        var $msg = $('.J_error_msg')
        if (!!show) $msg.html('<i class="glyphicon glyphicon-remove"></i> ' + msg).removeClass('hidden');
        else $msg.addClass('hidden');
        if (cb !== undefined) cb();
    }

    $('input').on('focus', function() {
        if (!$('.J_error_msg').hasClass('hidden')) {
            $('.J_error_msg').addClass('hidden')
        }
    })

    //回车登录
    $('input').on('keydown', function(e) {
        var ev = document.all?window.event:e;
        if (ev.keyCode == 13) {
            login()
        }
    })

})
