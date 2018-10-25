<?php
$this->params = ['css' => 'css/z.css', 'js' => 'js/z.js'];

$this->title = '活动说明';
?>
<main class="container">
	<div class="free-shipping" id="top">
	<img class="banner1" src="/images/free_shipping/banner1.jpg">
	<img class="banner2" src="/images/free_shipping/banner2.png">
	<div class="goods-contain" id="J_list_box">
		
	</div>
	<a href="#top" data-link-section class="to-top hidden"><img  class="to-img" src="/images/free_shipping/top.png"></a>
	<img class="ceiling-img hidden" src="/images/free_shipping/xuanfuk.png">
</div>
</main>
<script type="text/template" id="J_tpl_list">
	{@each _ as it}
		<div class="goods-detail">
			<a href="/goods/detail?id=${it.id}">
				<img class="baoyou-img" src="/images/free_shipping/baoyou.png">
				<img class="goods-img" src="${it.main_image}?x-oss-process=image/resize,w_185,h_185,limit_1,m_lfit">
				<span class="cut-line"></span>
				<span class="price"><span>￥</span>${it.price|price}</span>
				<span class="describe">${it.title}</span>
			</a>
		</div>
	{@/each}
</script>