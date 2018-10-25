$(function() {
    $('.account-header').removeClass('hidden');
    $('.pay-box').on('click', function() {
        $(this).addClass('select').siblings().removeClass('select');
    })
    //限制充值金额
    $('#recharge_amount').on('keyup', function () {
        var number = $(this).val().replace(/[^0-9.]/g, '');
        $(this).val(number);
    })
    //确认充值
    $('.J_go_pay').on('click', function () {
        var nub = $('#recharge_amount').val().trim();
        var payment = 2;
        if (nub == '') {
            alert('充值金额不能为空！')
            return
        }
        var x = nub.match(/\./g);
        if (x != null) {
            if (x.length > 1) {
                alert('数字不合法！')
                return
            }
            var i = nub.indexOf('.');
            var len = nub.substring(i)
            if (len.length > 3) {
                alert('最多输入两位小数！')
                return
            }
        }
        if (nub > 100000000 || nub < 0.01) {
            alert('充值金额应在0.01-100000000之间！')
            return
        }
        if (payment == undefined) {
            alert('请选择充值方式！')
            return
        }
        function rechargeCB(data) {
            window.location.href = data.url
        }

        var data = {
            rmb: nub,
            recharge_method: payment
        }
        $('.J_go_pay').addClass('disabled');
        function errFn(data) {
            $('.J_go_pay').removeClass('disabled');
            alert(data.errMsg)
        }

        requestUrl('/account/index/recharge', 'POST', data, rechargeCB, errFn);
    })

})