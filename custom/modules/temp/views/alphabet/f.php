<?php
$this->params = ['css' => 'css/f.css', 'js' => 'js/f.js'];
?>
<style type="text/css">
	body {
		background-color: #f6f6f6;
		margin: 0;
		padding: 0;
		user-select: none;
	}
</style>
<div class="seckill-container" style="padding-top: 20px">
	<div class="header hidden">
		<div class="header-contain"  id="J_seckill_timer">
			<img src="/images/seckill/clock_icon_2.png">
			<span id="timer_text">距离开始还剩：</span>
			<span class="countdown countstyle">
				<span class="count-hour countstyle" id="timer_hour">00</span>:
				<span class="count-minute countstyle" id="timer_minute">00</span>:
				<span class="count-second countstyle" id="timer_second">00</span>
			</span>
		</div>
	</div>
	<div class="seckill-goods" id="J_product_box">
		
	</div>
</div>
<script type="text/template" id="J_tpl_pro">
	{@each data as it}
		<div class="goods-detail">
			{@if temp === 0}
				<a href="javascript:;">
			{@else}
				<a href="/product?id=${it.id}">
			{@/if}
				<img src="${it.main_image}?x-oss-process=image/resize,w_230,h_230,limit_1,m_lfit">
			</a>
			<p class="describe" title="${it.title}">${it.title}</p>
			<p class="price">
				专享价：￥
				<span>${it.price|price}</span>
			</p>
			<div class="button-contain">
				<div class="shopping-cart hidden">加入购物车</div>
				{@if temp === 0}
					<div class="buying-now"><a href="javascript:;">未开始</a></div>
				{@else}
					<div class="buying-now"><a href="/product?id=${it.id}">立即购买</a></div>
				{@/if}
			</div>
		</div>
	{@/each}
</script>