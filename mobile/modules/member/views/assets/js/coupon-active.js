$(function () {
    // Carousel
    $(".coupon-activation").on('click','button',function () {
        var data = isjudge();
        if(data == true){
            return
        }
        function couponActiv(datas) {
                alert("恭喜您优惠劵激活成功！");
                $('#coupon-no').val('');
                $('#password').val('');
                $('#coupon-no').removeClass('input-active');
                $('#password').removeClass('input-active');
        }
        requestUrl('/member/coupon/validate-ticket', 'post', data, couponActiv);
    })
    $(".coupon-input-no-pass").on('click','img',function () {
        if($('#password').attr('type') == 'text'){
            $('#password').attr('type','password');
            return
        }
        if($('#password').attr('type') == 'password'){
            $('#password').attr('type','text');
            return
        }
    })
    function isjudge() {
        var no =  $('#coupon-no').val();
        var pass = $('#password').val();
        if(no == "" || no == undefined){
            $('#coupon-no').val('');
            $('#coupon-no').attr('placeholder','请输入有效的序列号！');
            $('#coupon-no').addClass('input-active');
            return true;
        }
        if(no.length != 15){
            $('#coupon-no').val('');
            $('#coupon-no').attr('placeholder','请输入有效的序列号！');
            $('#coupon-no').addClass('input-active');
            return true;
        }
        if(pass == ""){
            $('#password').val('');
            $('#password').attr('placeholder','请输入有效的密码！');
            $('#password').addClass('input-active');
            return true;
        }
        if(pass.length != 8){
            $('#password').val('');
            $('#password').attr('placeholder','请输入有效的密码！');
            $('#password').addClass('input-active');
            return true;
        }
        var data = {
            code:no,
            password:pass
        }
        return data;
    }
});
