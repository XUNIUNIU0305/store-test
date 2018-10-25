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
<link rel="stylesheet" type="text/css" href="/wechat-assets/css/normalize.css">
<link rel="stylesheet" type="text/css" href="/wechat-assets/css/success.css?v=<?=filemtime($filePath . 'css/success.css') ?>">
<body>
	<div class="admin-pay-success">
		<div class="result">
			<img src="/images/newJoin/success.png">
		</div>
		<p class="title">支付成功</p>
		<!-- <div class="loading">
			<img src="/images/newJoin/text_login_gif.gif">
		</div> -->
		<p class="h3">即将跳往登录页面...</p>
		<p class="h2" id="J_num">3</p>
		<div class="content hidden">
			<p class="title-1">您的<span>注册码：</span></p>
			<p class="code"></p>
			<p class="title-1">将于5分钟后发送至您的手机！</p>
			<p class="title-2">快去注册账号吧！</p>
		</div>
		<div class="title-3 hidden">
			<p>
				工作人员将在2个工作日内与您联系，
				<br>
				现场协助您入住商城，
				<br>
				解锁更多新功能！
			</p>
		</div>
		<p class="title-4 hidden">（请提前备好营业执照及法人身份证）</p>
		<a class="btn" id="jump" href="http://m.9daye.com.cn/member/login">马上登录</a>
	</div>
</body>
<div id="host" class="hidden"><?= Yii::$app->params['MOBILE_Hostname'] ?></div>
<script type="text/javascript" src="/wechat-assets/js/zepto.min.js"></script>
<script type="text/javascript" src="/wechat-assets/js/fastclick.js"></script>
<script type="text/javascript" src="/wechat-assets/js/url.min.js"></script>
<script type="text/javascript" src="/wechat-assets/js/success.js?v=<?= filemtime($filePath . 'js/success.js') ?>"></script>
</html>
