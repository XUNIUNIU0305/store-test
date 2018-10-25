<?php
$this->params = ['css' => 'css/index.css', 'js' => 'js/index.js'];
?>
<div class="business-login">
	<div class="business-login-top"></div>
	<div class="business-login-title">
		<p>创智汇业务管理平台</p>
	</div>
	<div class="content">
		<form class="login form-horizontal">
			<div class="head">用户登录</div>
			<div class="form-group error-msg">
                <div class="">
                	<!-- addClass 'invisible' to hide the error msg -->
                    <p class="form-control-static text-center text-danger invisible J_error_msg"><i class="glyphicon glyphicon-remove"></i></p>
                </div>
            </div>
			<div class="login-input">
				<div class="form-group">
	                <label for="username" class="col-xs-2 control-label">
	                	<i class="glyphicon glyphicon-user"></i>
	                </label>
	                <div class="col-xs-8">
	                    <input type="text" class="form-control" id="username" placeholder="请输入您的用户名或手机号">
	                </div>
	            </div>
	            <div class="form-group">
	                <label for="password" class="col-xs-2 control-label">
	                	<i class="glyphicon glyphicon-lock"></i>
	                </label>
	                <div class="col-xs-8">
	                    <input type="password" class="form-control" id="password" placeholder="请输入您的密码">
	                </div>
	            </div>
		        <div class="form-group">
	                <label for="validate" class="col-xs-2 control-label">
	                	<i class="glyphicon glyphicon-ok-sign"></i>
	                </label>
	                <div class="col-xs-6">
	                    <input type="text" class="form-control" id="validate" maxlength="4" placeholder="请输入验证码">
	                </div>
	                <div class="col-xs-3 captcha-box">
	                    <p class="form-control-static text-center text-white J_verify_captcha"></p>
	                </div>
	            </div>
	            <div class="form-group">
		            <label class="col-xs-2 control-label"></label>
		            <div class="col-xs-8" id="J_login_btn"><a class="btn login-btn btn-block">登 录</a></div>
		        </div>
		        <div class="form-group">
	                <div class="col-xs-4 register-tip-forget">
	                	<a href="/password" class="pull-right btn-link btn btn-xs">忘记密码？</a>
	                </div>
	                <div class="col-xs-4 col-xs-offset-2 register-tip">
	                	<a href="/register" class="pull-right btn-link btn btn-xs">立即注册</a>
	                </div>
	            </div>
			</div>
		</form>
	</div>
	<footer class="footer">
		<h5><a href="http://www.miitbeian.gov.cn" target="_blank">苏ICP备16057447号-2</a></h5>
	    <h5>创智汇（苏州）电子商务有限公司版权所有</h5>
	    <h5>电话：400-0318-119&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;邮箱：<a href="mailto: kf@9daye.com.cn">kf@9daye.com.cn</a></h5>
	    <div class="bottom"></div>
	</footer>
</div>