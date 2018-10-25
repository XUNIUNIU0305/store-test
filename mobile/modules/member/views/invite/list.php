<?php
$this->params = ['js' => 'js/invite-list.js','css'=>'css/invite-list.css'];
?>
<nav class="top-nav">
    <a href="/member/invite">
        <img src="/images/account/arrow-right.png" height="14">
    </a>
    <div class="title">
        邀请记录
    </div>
</nav>
<div class="qr-code-list">
	<div class="header">
		<div class="info">
			<p>总邀请数量：<span id="J_invite_num"></span></p>
			<span class="btn" id="J_search_btn"><img src="/images/qrCode/search.png"></span>
		</div>
		<div class="search hidden">
			<input type="number" placeholder="请输入门店账号或手机号" id="J_search_input" name="">
			<span class="btn" id="J_close_btn">取消</span>
			<span class="btn-search" id="J_search_sure"><img src="/images/qrCode/search02.png"></span>
		</div>
	</div>
	<div class="code-list">
		<div class="list-head">
			<span>门店账号</span>
			<span>状态</span>
			<span>手机号</span>
		</div>
		<div class="list-items" id="J_code_list">
			
		</div>
	</div>
</div>