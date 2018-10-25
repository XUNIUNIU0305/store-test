<?php
$this->params = ['js' => 'js/water.js','css'=>'css/water.css'];
?>
<nav class="top-nav">
    <a href="/">
        <img src="/images/account/arrow-right.png" height="14">
    </a>
    <div class="title">
        领水券管理
    </div>
</nav>
<div class="water-coupon-list">
	<div class="head-nav">
		<p class="active" data-status="0"><span>可使用</span></p>
		<p data-status="1"><span>已使用</span></p>
	</div>
	<div class="coupon-list" id="J_coupon_list">
		
	</div>
</div>
<script type="text/template" id="J_tpl_list">
	{@each list as it}
		{@if it.used == 1}
		<div class="item disabled">
		{@else}
		<div class="item">
		{@/if}
			<p class="title">领取码：<span>${it.pick_id}</span></p>
			<img src="/images/water_coupon/word_1yuan.png">
			<p class="time">购买时间：<span>${it.pay_time}</span></p>
			{@if it.used == 0}
			<p class="address">使用地点：<span>${it.area}</span></p>
			{@else}
			<p class="time">购买时间：<span>${it.pay_time}</span></p>
			<p class="address">使用时间：<span>${it.pick_time}</span></p>
			{@/if}
		</div>
	{@/each}
</script>