<?php
$this->params = ['js' => 'js/search-index.js', 'css' => 'css/search-index.css'];
?>
<main class="container search">


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
				<span>清新剂七字分类</span>
				”相关商品~
			</div>
		</div>
		<div class="empty hidden error2">
			<img class="empty-img" src="/images/custom_search/empty2.png">
			<div class="empty-describe">
				没有找到“
				<span>清新剂七字分类</span>
				”相关分类和商品~
			</div>
		</div>
		<!-- 找到商品 -->
		<div class="search-result" id="J_goods_contain">

		</div>
		<!-- 推荐商品 -->
		<div class="recommend-goods hidden">
			<img class="recom-img" src="/images/custom_search/title.png" >
			<div class="recommend-detail" id="J_recommend_contain">
				
			</div>
		</div>
		<!-- 返回顶部 -->
		<a href="javascript:void(0)" class="go-top" data-link-section>
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
	<p class="search-cancel hidden" id="J_cancel">取消</p>
</div>
<!-- 搜索分类展示 -->
<div class="result-sort-contain">
	<div class="result-sort">
		<div class="sort-title">
			<p>分类</p>
			<div class="sort-words" id="J_sort_title">
			</div>
			<div class="sort-img">
				<img src="/images/custom_search/shadow_fenlei.png">
				<img class="more-sort" src="/images/custom_search/icon_30_more.png">
			</div>
		</div>
		<div class="sort-more-detail hidden" id="J_sort_contain">
		</div>
	</div>
</div>
<script type="text/template" id="T_sort_list">
	{@each _ as it,index}
		{@if index < 3}
		<span data-id="${it.id}">${it.title}</span>
		{@/if}
	{@/each}
</script>

<script type="text/template" id="J_sort_list">
	{@each _ as it}
		<span data-id="${it.id}">${it.title}</span>
	{@/each}
</script>

<script type="text/template" id="J_goods_list">
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
