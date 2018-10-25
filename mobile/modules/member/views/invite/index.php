<?php
$this->params = ['js' => 'js/invite-index.js','css'=>'css/invite-index.css'];
?>
<nav class="top-nav">
    <a href="/">
        <img src="/images/account/arrow-right.png" height="14">
    </a>
    <div class="title">
        二维码邀请
    </div>
</nav>
<div class="qr-code-invite">
	<p class="title-1">诚邀您加入九大爷平台</p>
	<div class="img">
		<img src="/member/invite/code">
	</div>
	<p class="code">序列号：<span id="J_code"></span></p>
	<p class="address">邀请人：<span id="J_address"></span></p>
	<a class="btn record" id="J_invite">邀请记录</a>
	<!-- <a class="btn presentation">活动说明</a> -->
</div>