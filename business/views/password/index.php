<?php
$this->params = ['css' => 'css/password.css', 'js' => 'js/password.js'];
?>
<div class="business-register">
	<div class="business-register-head">
		<div class="content">
			<p>创智汇业务管理平台 | 找回密码</p>
		</div>
	</div>
	<div class="business-register-content col-xs-6">
		<!-- register-title加class: active切换状态 -->
		<div class="register-title"><span class="text"></span></div>
		<div class="register-form">
			<form class="form-horizontal panel-body">
	            <div class="form-group ">
	                <label class="col-xs-4" for="register_id">账 号：</label>
	                <div class="col-xs-4">
	                    <input type="text" class="form-control" id="register_id" maxlength="8" placeholder="">
	                </div>
	                <div class="col-xs-3">
	                	<p class="high-lighted error-msg hidden"><i class="glyphicon glyphicon-remove-sign"></i> <span>输入错误！</span></p>
	                </div>
	            </div>
	            <div class="form-group ">
	                <label class="col-xs-4" for="user_mobile">手机号：</label>
	                <div class="col-xs-4">
	                    <input type="text" class="form-control" id="user_mobile" maxlength="11" placeholder="">
	                </div>
	                <div class="col-xs-3">
	                	<p class="high-lighted error-msg hidden"><i class="glyphicon glyphicon-remove-sign"></i> <span>输入错误！</span></p>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="col-xs-4" for="verify">验证码：</label>
	                <div class="col-xs-2">
	                    <input type="text" class="form-control" id="verify" maxlength="6">
	                </div>
	                <div class="col-xs-2">
	                    <a class="btn btn-block business-btn" id="J_get_verify">获取验证码</a>
	                </div>
	                <div class="col-xs-3">
	                	<p class="high-lighted error-msg hidden"><i class="glyphicon glyphicon-remove-sign"></i> <span>输入错误！</span></p>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="col-xs-4" for="password">新密码：</label>
	                <div class="col-xs-4">
	                    <input type="password" class="form-control" id="password" maxlength="20" placeholder="">
	                </div>
	                <div class="col-xs-3">
	                	<p class="high-lighted error-msg hidden"><i class="glyphicon glyphicon-remove-sign"></i> <span>输入错误！</span></p>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="col-xs-4" for="confrim">确认新密码：</label>
	                <div class="col-xs-4">
	                    <input type="password" class="form-control" id="confrim" maxlength="20" placeholder="">
	                </div>
	                <div class="col-xs-3">
	                	<p class="high-lighted error-msg hidden"><i class="glyphicon glyphicon-remove-sign"></i> <span>输入错误！</span></p>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="col-xs-4" for="nickname"></label>
	                <div class="col-xs-4">
	                    <a class="btn col-xs-12 business-btn" id="J_register_btn">确定</a>
	                </div>
	            </div>
	        </form>
		</div>
	</div>
	<footer class="footer">
		<h5><a href="http://www.miitbeian.gov.cn" target="_blank">苏ICP备16057447号-2</a></h5>
	    <h5>创智汇（苏州）电子商务有限公司版权所有</h5>
	    <h5>电话：400-0318-119&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;邮箱：<a href="mailto: kf@9daye.com.cn">kf@9daye.com.cn</a></h5>
	</footer>
</div>

