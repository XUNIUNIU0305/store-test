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
<link rel="stylesheet" type="text/css" href="/wechat-assets/css/scanFail.css?v=<?= filemtime($filePath . 'css/scanFail.css') ?>">
<body>
	<main class="contanier">
		<div class="admin-scan-fail">
			<div class="img">
				<img src="/images/newJoin/chahua.jpg">
				<p class="title-1">二维码失效</p>
				<p class="title-2">此二维码已经失效了哦</p>
				<p class="title-3">您可以联系<span>运营商</span>或<span>微信客服</span>解决</p>
			</div>
			<a class="btn" id="close" href="javascript:void(0);">我知道了</a>
		</div>
	</main>
</body>
<script type="text/javascript" src="/wechat-assets/js/zepto.min.js"></script>
<script type="text/javascript" src="/wechat-assets/js/fastclick.js"></script>
<script type="text/javascript" src="/wechat-assets/js/url.min.js"></script>
<script type="text/javascript" src="/wechat-assets/js/scanfail.js?v=<?= filemtime($filePath . 'js/scanfail.js') ?>"></script>
<script type="text/javascript" src='http://res.wx.qq.com/open/js/jweixin-1.2.0.js'></script>
</html>
