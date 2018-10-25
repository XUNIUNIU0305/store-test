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
<link rel="stylesheet" type="text/css" href="/wechat-assets/css/failed.css?v=<?= filemtime($filePath . 'css/failed.css') ?>">
<body>
	<main class="contanier">
		<div class="admin-pay-failed">
			<div class="result">
				<img src="/images/newJoin/fail.png">
			</div>
			<p class="title">支付失败</p>
			<p class="title-1">竟然支付失败了，<span>重新扫码</span>支付试试吧！</p>
			<a class="btn" id="reset" href="javascript:;">重新扫码</a>
		</div>
	</main>
</body>
<script type="text/javascript" src="/wechat-assets/js/zepto.min.js"></script>
<script type="text/javascript" src="/wechat-assets/js/fastclick.js"></script>
<script type="text/javascript" src="/wechat-assets/js/url.min.js"></script>
<script type="text/javascript" src="/wechat-assets/js/fail.js?v=<?= filemtime($filePath . 'js/fail.js') ?>"></script>
<script type="text/javascript" src='http://res.wx.qq.com/open/js/jweixin-1.2.0.js'></script>
</html>
