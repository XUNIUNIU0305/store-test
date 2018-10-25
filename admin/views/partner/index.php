<?php
    $filePath = __DIR__ . '/../../web/wechat-assets/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="yes" name="apple-touch-fullscreen" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title></title>
</head>
<link rel="stylesheet" type="text/css" href="/wechat-assets/css/normalize.css?v=<?= filemtime($filePath . 'css/normalize.css') ?>">
<link rel="stylesheet" type="text/css" href="/wechat-assets/css/index.css?v=<?= filemtime($filePath . 'css/index.css') ?>">
<body>
	<div class="new-invite-custom">
		<img src="/images/invite_pay/2018-04/1.jpg" alt="">
		<img src="/images/invite_pay/2018-04/2.jpg" alt="">
		<img src="/images/invite_pay/2018-04/5_03.png" alt="">
		<div class="invite-info">
			<p>
				<span id="promoterID"></span>
				推荐您成为九大爷平台会员
			</p>
		</div>
		<div class="footer">
			<img src="/images/invite_pay/2018-04/5_03.jpg" alt="">
			<a href="#" id="J_open_info" class="invite_now"></a>
		</div>
	</div>
	<div class="write-custom-info">
		<div class="close">
        	<img src="/images/invite_pay/new/close_58_icon.png" alt="">
    	</div>
		<div class="form">
			<div class="mobile">
				<p class="label">手机号码</p>
				<input type="number" id="mobile" maxlength="11" name="" placeholder="请输入您的手机号" />
			</div>
			<div class="mobile">
				<p class="label">设置密码</p>
				<input type="password" id="J_pass_wd" maxlength="40" name="" placeholder="请设置您的密码" />
			</div>
			<div class="mobile">
				<p class="label">确认密码</p>
				<input type="password" id="J_confirm_passwd" maxlength="40" name="" placeholder="请确认您的密码" />
			</div>
			<div class="group">
				<p class="label">验证码</p>
				<input type="text" name="" id="captcha" maxlength="6" placeholder="请输入验证码" />
				<span class="btn" id="captcha-btn">获取验证码</span>
			</div>
			<span class="btn-submit" id="pay_now">
				立即注册
			</span>
		</div>
	</div>
</body>
<script type="text/javascript" src='http://res.wx.qq.com/open/js/jweixin-1.2.0.js'></script>
<script type="text/javascript" src="/wechat-assets/js/zepto.min.js"></script>
<script type="text/javascript" src="/wechat-assets/js/fastclick.js"></script>
<script type="text/javascript" src="/wechat-assets/js/url.min.js"></script>
<script type="text/javascript" src="/wechat-assets/js/common.js?v=<?= filemtime($filePath . 'js/common.js') ?>"></script>
<script type="text/javascript" src="/wechat-assets/js/index.js?v=<?= filemtime($filePath . 'js/index.js') ?>"></script>
</html>
	
