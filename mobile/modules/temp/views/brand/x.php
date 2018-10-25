<?php
$this->params = ['css' => 'css/x.css', 'js' => 'js/x.js'];

$this->title = '专购活动';
?>
<style type="text/css">
	main.container {
		background: #f2f2f2;
	}
</style>
<main class="container">
	<div class="seckill-container-1007">
		<div class="seckill-header hidden">
			<div class="header-contain">
				<img src="/images/seckill_1007/clock_icon.png">
				<span id="timer_text">距离开始还剩：</span>
				<span class="countdown countstyle">
					<span class="count-hour countstyle" id="timer_hour">00</span>:
					<span class="count-minute countstyle" id="timer_minute">00</span>:
					<span class="count-second countstyle" id="timer_second">00</span>
				</span>
			</div>
		</div>
		<div class="product-container" id="J_product_box">
			<script type="text/template" id="J_tpl_pro">
				{@each data as it}
					<div class="item">
						<div class="img">
							<img src="${it.main_image}?x-oss-process=image/resize,w_110,h_110,limit_1,m_lfit" alt="">
						</div>
						<div class="detail">
							<p class="title">${it.title}</p>
							<p class="price"><small>￥</small>${it.price|price}</p>
							<p class="btn-box">
								{#<a>加购物车</a>}
								{@if start === false}
									<a href="javascript:;">未开始</a>
								{@else}
									<a href="/goods/detail?id=${it.id}">立即购买</a>
								{@/if}
							</p>
						</div>
					</div>
				{@/each}
			</script>
		</div>
		<div class="footer">
			<p>——————&nbsp;&nbsp;哎呀，到底了呀！&nbsp;&nbsp;——————</p>
		</div>
		<div class="join-cart-tip">
			已加入购物车
		</div>
	</div>
</main>
