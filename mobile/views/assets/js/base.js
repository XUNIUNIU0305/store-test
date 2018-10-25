var _user_login_status=0;

$(function() {
	function checkLoginStatus(){
        function success(data){
            window.CUSTOM_USER_LEVEL = data.level
            _user_login_status=data.status;
            if(data.status==1){
                $("#J_login_btn span").html('注销');
                $("#J_login_btn").attr('href', '/member/login/logout?ref=/index');
                return true;
            }else{
                $("#J_login_btn span").html('登录');
                $("#J_login_btn").attr('href', '/member/login/index');
                return false;
            }
        };

        requestUrl("/index/get-user-status", 'GET','',success, function() {
            
        }, false);
    }

    //检测用户是否已登录
    checkLoginStatus();
})