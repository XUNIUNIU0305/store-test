<?php

?>

<div class="apx-cms-login user-sys">
    <div class="apx-cms-login-box fade-in col-xs-8 col-sm-6 col-md-4 col-lg-3">
        <h1 class="text-center">
            <img src="/images/new_icon.png" width="240">
        </h1>
        <h3 class="text-center">店铺管理系统登录</h3>
        <form class="form-horizontal col-xs-10 col-xs-offset-1" role="form">
            <div class="form-group error-msg">
                <div class="col-xs-12">
                	<!-- addClass 'hidden' to hide the error msg -->
                    <p class="form-control-static text-center text-danger hidden J_error_msg"><i class="glyphicon glyphicon-remove"></i></p>
                </div>
            </div>
            <div class="form-group">
                <label for="username" class="col-xs-2 control-label">
                	<i class="glyphicon glyphicon-user"></i>
                </label>
                <div class="col-xs-10">
                    <input type="text" class="form-control" id="username" placeholder="请输入您的用户名">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-xs-2 control-label">
                	<i class="glyphicon glyphicon-lock"></i>
                </label>
                <div class="col-xs-10">
                    <input type="password" class="form-control" id="password" placeholder="请输入您的密码">
                </div>
            </div>
            <div class="form-group">
                <label for="validate" class="col-xs-2 control-label">
                	<i class="glyphicon glyphicon-ok-sign"></i>
                </label>
                <div class="col-xs-7">
                    <input type="text" class="form-control" id="validate" maxlength="4" placeholder="请输入验证码">
                </div>
                <div class="col-xs-3">
                    <p class="form-control-static text-center text-white J_verify_captcha"></p>
                </div>
            </div>
            <div class="form-group">
                <div class="btn btn-danger btn-block btn-lg J_login_btn">登录</div>
            </div>
        </form>
    </div>
</div>