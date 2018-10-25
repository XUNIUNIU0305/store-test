$(function() {
    $('.account-header').removeClass('hidden');
    // 验证码 与 刷新
    function getCaptchaImg() {
        $.ajax({
            url: "/captcha",
            method: "GET",
            data: {
                'refresh': Math.random() + ''
            }
        })
            .done(function (data) {
                // append image node
                $('.J_verify_captcha').empty()
                    .css({
                        'padding': 0
                    }).append('<img src="' + data.url + '">')
                // set style and event listener
                $('.J_verify_captcha img').css({
                    'float': 'left',
                    'background': '#fff',
                    'max-height': '34px',
                    'padding-left': '4px',
                    'margin': '0 8px'
                })
                    .on('click', getCaptchaImg);
            })
            .fail(function () {
                setTimeout(function () {
                    getCaptchaImg();
                }, 3000);
            })
    }
    getCaptchaImg();
    $('#J_change_captcha').on('click', function() {
        getCaptchaImg();
    })
    // 报错提示
    function showErr(text) {
        $('.error-msg').html(text).removeClass('hidden');
    }
    //修改密码
    $('#J_change_pwd').off('.edit').on('click.edit', function () {
        var pwd_old = $('#pwd_old').val();
        var new_pwd = $('#new_old1').val();
        var confirm_pwd = $('#new_old2').val();
        var captcha = $('#validate').val();
        if (pwd_old == '' || new_pwd == '' || confirm_pwd == '' || captcha == '') {
            showErr('请填写完整！')
            return
        }
        var resultPW = new_pwd.search(/[^0-9a-zA-Z]/g);
        if (resultPW != -1) {
            showErr('密码只能为数字或字母！')
            return
        }
        if (new_pwd != confirm_pwd) {
            showErr('两次密码不一致！')
            return
        }
        if (new_pwd.length < 8) {
            showErr('密码长度至少为8位！');
            return
        }
        $('#J_change_pwd').addClass('disabled');
        $('#J_change_pwd').text('密码修改中...');
        var data = {
            origin_passwd: pwd_old,
            new_passwd: new_pwd,
            confirm_new_passwd: confirm_pwd,
            captcha: captcha
        }

        function submitCB(data) {
            $('#J_change_pwd').removeClass('disabled');
            $('#J_change_pwd').text('修改密码');
            alert('密码修改成功，请重新登录！')
            function outCB(data) {
                window.location.href = '/login'
            }

            requestUrl('/login/logout', 'GET', '', outCB);
        }

        function errCB(data) {
            $('#J_change_pwd').removeClass('disabled');
            $('#J_change_pwd').text('修改密码');
            getCaptchaImg()
            alert(data.data.errMsg)
        }
        $('.error-msg').addClass('hidden').html('');
        requestUrl('/account/password/modify', 'POST', data, submitCB, errCB);
    })
})