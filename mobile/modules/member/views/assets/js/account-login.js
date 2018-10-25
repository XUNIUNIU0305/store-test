/**
 * Created by Administrator on 2017/5/22.
 */
$(function(){
    if (url('?status') === '0') {
        $('.wechat-tip').removeClass('hidden')
    }
    // 登陆
    function login() {
        $('.J_login_btn').addClass('disabled').off('.login');
        var tip = {
                account: '用户名',
                passwd: '密码',
            },
            info = {
                account: $('#username').val(),
                passwd: $('#password').val(),
            };

        // 空值判断
        $.each(info, function(key, val) {
            if (!val) {
               $(".error-msg").html(tip[key] + '不能为空').show();
                $('.J_login_btn').removeClass('disabled').on('click.login', login)
                throw tip[key] + '不能为空';
            }
        });

        function loginCB(data) {
            $('.J_login_btn').removeClass('disabled').on('click.login', login);
            location.href = data.url;
        }
        function errCB(data) {
            $('.J_login_btn').removeClass('disabled').on('click.login', login);
            $(".error-msg").html(data.data.errMsg).show();

        }
        requestUrl('/member/login/check-login', 'POST', info, loginCB, errCB);
    }
    $(".error-msg").hide();
    $('.J_login_btn').on('click.login', login);

    $('input').on('focus', function() {
          $('.error-msg').hide();
    })

    //回车登录
    $('input').on('keydown', function(e) {
        var ev = document.all?window.event:e;
        if (ev.keyCode == 13) {
            login()
        }
    })


})