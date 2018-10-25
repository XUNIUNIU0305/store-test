$(function(){
    $('input').on('focus',function () {
        $('.error-control').addClass('error-msg')
    })
    // $('input').on('blur',function(){
    //     var maxLength=$(this).attr("maxlength");
    //     var minLength=$(this).attr("minlength");
    //     var currentLength=$(this).val().length;
    //     if(currentLength<minLength||currentLength>maxLength){
    //         $(this).parents('.row').find('.error-control').removeClass('error-msg').html('<i class="glyphicon glyphicon-remove"></i>新密码长度不正确!')
    //         return;
    //     }

    //     //验证个件
    //     switch($(this).attr("id")){
    //         case 'password1':
    //             var resultPW = $(this).val().search(/[^0-9a-zA-Z]/g);
    //             if (resultPW != -1) {
    //                 $('.error-control').eq(0).html('<i class="glyphicon glyphicon-remove"></i>新密码不合法!').removeClass('error-msg')
    //                 return;
    //             }
    //             break;
    //         case 'password':
    //             var resultPW = $(this).val().search(/[^0-9a-zA-Z]/g);
    //             if (resultPW != -1) {
    //                 $('.error-control').eq(1).html('<i class="glyphicon glyphicon-remove"></i>确认密码不合法!').removeClass('error-msg')
    //                 return;
    //             }
    //             if($(this).val()!=$("#password1").val()){
    //                 $('.error-control').eq(1).html('<i class="glyphicon glyphicon-remove"></i>两次填写密码不相同!').removeClass('error-msg')
    //                 return;
    //             }
    //             break;
    //         case 'mobile':
    //             var resultMB = $(this).val().search(/0?(1)[0-9]{10}/);
    //             if (resultMB == -1) {
    //                 $('.error-control').eq(2).html('<i class="glyphicon glyphicon-remove"></i>手机号码不正确!').removeClass('error-msg')
    //                 return;
    //             }
    //             break;
    //     }
    // });



    // get sms
    var timerAll = [],
        intervalAll = [];
    $('.J_get_verify_sms').off("click").on("click",function (e) {
        var mobile = $('#mobile').val();
        if(/^\s*$/.test(mobile)){
            $('.error-control').eq(2).html('<i class="glyphicon glyphicon-remove"></i>手机号码不能为空!').removeClass('error-msg')
            return
        }
        var resultMB = mobile.search(/0?(1)[0-9]{10}/);
        if (resultMB == -1) {
            $('.error-control').eq(2).html('<i class="glyphicon glyphicon-remove"></i>手机号码不正确!').removeClass('error-msg')
            return
        }

        var _data={mobile:mobile,type:1,};

        var $that=$(this);

        //启用计时启
        function startTimer(){
            var timer, interval;
            e.preventDefault();
            var $this =$that;
            var countDown = 60;
            // disable it
            // if ($this.hasClass('disabled')) return;
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

        function submitCB(data){
            startTimer();
        }
        //错误返回
        function errCB(data) {
            $('.J_get_verify_sms').removeClass('disabled');
            alert(data.data.errMsg)
        }

        $that.addClass('disabled');
        requestUrl('/sms/send', 'GET', _data, submitCB, errCB)

    });


    //验证相关信息
    $(".J_btn_change_password").off("click").on("click",function(){
        var new_password=$("#password1").val();
        var confirm_password=$("#password").val();
        var mobile=$("#mobile").val();
        var verifyCode=$("#validate").val();

        if(new_password==""){
            $('.error-control').eq(0).html('<i class="glyphicon glyphicon-remove"></i>新密码不能为空!').removeClass('error-msg')
            return;
        }
        if(confirm_password==""){
            $('.error-control').eq(1).html('<i class="glyphicon glyphicon-remove"></i>确认密码不能为空!').removeClass('error-msg')
            return;
        }

        var resultPW = new_password.search(/[^0-9a-zA-Z]/g);
        if (resultPW != -1) {
            $('.error-control').eq(0).html('<i class="glyphicon glyphicon-remove"></i>请填写正确的密码！!').removeClass('error-msg')
            return
        }

        if (new_password.length < 8) {
            $('.error-control').eq(0).html('<i class="glyphicon glyphicon-remove"></i>密码长度至少为8位!').removeClass('error-msg')
            return
        }

        if(new_password!=confirm_password){
            $('.error-control').eq(1).html('<i class="glyphicon glyphicon-remove"></i>两次填写密码不相同!').removeClass('error-msg')
            return;
        }

        if(mobile==""){
            $('.error-control').eq(2).html('<i class="glyphicon glyphicon-remove"></i>手机号码不能为空!').removeClass('error-msg')
            return
        }

        var resultMB = mobile.search(/0?(1)[0-9]{10}/);
        if (resultMB == -1) {
            $('.error-control').eq(2).html('<i class="glyphicon glyphicon-remove"></i>手机号码不正确!').removeClass('error-msg')
            return
        }

        if(verifyCode==""){
            $('.error-control').eq(3).html('<i class="glyphicon glyphicon-remove"></i>验证码不能为空!').removeClass('error-msg')
            return
        }
        $(".error-control").addClass('error-msg');
        var _data={
            new_password:new_password,
            confirm_password:confirm_password,
            mobile:mobile,
            verify_code:verifyCode
        };
        function submitCB(data){
            $('.find-success').removeClass('hidden')
            $('.forget-group').addClass('hidden')
            $('.back-login').removeClass('hidden')
            $('.J_btn_change_password').addClass('hidden')
        }
        function errCB(data){
            $('.find-false').removeClass('hidden')
            $('.back-forget').removeClass('hidden')
            $('.forget-group').addClass('hidden')
            $(".J_btn_change_password").removeClass("disabled").addClass('hidden')
            alert(data.data.errMsg)
        }
        $(this).addClass("disabled")
        requestUrl('/forget/modify', 'post', _data, submitCB, errCB)
    });



})