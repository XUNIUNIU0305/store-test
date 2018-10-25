<?php
$this->params = ['js' => 'js/search-detail.js', 'css' => 'css/search-detail.css'];
?>

<main class="container category-detail">
	<!-- 历史记录页面 -->
	<div class="search-history hidden">
		<div class="initial-show">
			<div class="hot-search">
				<div class="hot-title">热门搜索</div>
				<div class="hot-contain"></div>
			</div>
			<div class="hist-search">
				<div class="hist-title">
					<span>历史搜索</span>
					<a href='#' class="hist-cancel">清空</a>
				</div>
				<div class="hist-cantain">
					<ul class="uls-list">

					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- 查询展示列表 -->
	<div class="search-list hidden">
		<ul class="uls-list">

		</ul>
	</div>

	<!-- 搜索结果全部内容 -->
	<div class="result-detail-contail">
		<!-- 遮罩 -->
		<div class="close-mark hidden"></div>
		<!-- 无结果 -->
		<div class="empty hidden error1">
			<img class="empty-img" src="/images/custom_search/empty1.png">
			<div class="empty-describe">
				没有找到“
				<span></span>
				”相关商品~
			</div>
		</div>
		<div class="empty hidden error2">
			<img class="empty-img" src="/images/custom_search/empty2.png">
			<div class="empty-describe">
				没有找到“
				<span></span>
				”相关分类和商品~
			</div>
		</div>
		<!-- 找到商品 -->
		<div class="search-result" id="J_goods_contain">
			<!-- <div class="result-goods">
				<a href=""><img class="goods-png" src="/images/custom_search/goods1.png"></a>
				<p class="describe">卡仕达·镜e智能后视导航行车记录仪（四台包邮）</p>
				<p class="price">￥2620.00</p>
			</div> -->

		</div>
		<!-- 推荐商品 -->
		<div class="recommend-goods hidden">
			<img class="recom-img" src="/images/custom_search/title.png" >
			<div class="recommend-detail" id="J_recommend_contain">
				<!-- <div class="result-goods">
					<img class="goods-png" src="/images/custom_search/goods1.png">
					<p class="describe">卡仕达·镜e智能后视导航行车记录仪（四台包邮）</p>
					<p class="price">￥2620.00</p>
				</div> -->
			</div>
		</div>
		<!-- 返回顶部 -->
		<a href="#top" class="go-top" data-link-section>
			<img src="/images/custom_search/top.png">
		</a>
	</div>
</main>

<!-- 搜索框 -->
<div class="search-head" id="top">
	<div class="head-left">
		<img src="/images/custom_search/search_36_icon.png" class="search-img">
		<span></span>
		<input type="search" class="search-ipt" data-default="安程" placeholder="赶紧搜索“安程”">
	</div>
	<p class="search-cancel hidden">取消</p>
</div>
<!-- 搜索分类展示 -->
<div class="result-sort-contain">
	<div class="sort-title clearfix">
		<div class="sort-price sort-layout J_sort_btn" data-type="max_price">
			价格
			<img class="price-up up-img" src="/images/category_details/up_grey.jpg">
			<img class="price-down down-img" src="/images/category_details/down_grey.jpg">
		</div>
		<div class="sort-sale sort-layout J_sort_btn" data-type="sales">
			销量
			<img class="sale-up up-img" src="/images/category_details/up_grey.jpg">
			<img class="sale-down down-img" src="/images/category_details/down_grey.jpg">
		</div>
		<div class="more-img hidden">
			<img class="more-sort" src="/images/category_details/icon_26_1.png">
			筛选
		</div>
	</div>
	<!-- 更多分类 -->
	<div class="sort-more-detail hidden" id="J_sort_contain">
	</div>
</div>
<script type="text/template" id="J_sort_list">
	{@each _ as it}
		<span data-id="${it.id}">${it.title}</span>
	{@/each}
</script>

<script type="text/template" id="J_goods_list">
	{@each _ as it}
		<div class="result-goods">
			<a href="/goods/detail?id=${it.id}">
				<img class="goods-png" src="${it.main_image}?x-oss-process=image/resize,m_pad,h_240,w_240">
			</a>
			<p class="describe">
				${it.title}
			</p>
			<p class="price">
				￥${it.price.min}
			</p>
		</div>
	{@/each}
</script>

<script type="text/template" id="J_recommend_list">
	{@each _ as it}
		<div class="result-goods">
			<a href="/goods/detail?id=${it.id}">
				<img class="goods-png" src="${it.main_image}">
			</a>
			<p class="describe">
				${it.title}
			</p>
			<p class="price">
				￥${it.price.min}
			</p>
		</div>
	{@/each}
</script>
