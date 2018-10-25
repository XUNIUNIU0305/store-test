<?php
$this->params = ['js' => 'js/search-category.js', 'css' => 'css/search-category.css'];
?>

<main class="container category">
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

	<div class="category-contain">
		<ul class="category-detail"></ul>
	</div>
</main>
<!-- 搜索框 -->
<div class="search-head" id="top">
	<div class="head-left">
		<img src="/images/custom_category/search_36_icon.png" class="search-img">
		<span class="ipt-line"></span>
		<input type="search" class="search-ipt" data-default="安程" placeholder="赶紧搜索“安程”">
	</div>
	<p class="search-cancel hidden">取消</p>
</div>
<ul class="category-list"></ul>
