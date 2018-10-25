<?php
$this->params = ['js' => 'js/search-index.js', 'css' => 'css/search-index.css'];
$this->title = '九大爷平台 - 搜索';
?>
<div class="search-contain">
	<div class="main">
	    <div class="mian-serachcont">
	        <a href="/">首页</a>
	        <span>></span>
	        <span>搜索结果</span>
	    </div>
	    <div id="search_brand_category">
	    	<div class="main-brand">
	    	    <p>品牌：</p>
	    	    <ul class="main-brandlist" id="J_tpl_content">
	    	    </ul>
	    	</div>
	    	<div class="main-category clearfix">
	    	    <span class="sort_tilte">分类：</span>
	    	    <div id="J_sort_content" class="category-box">
	    	    	
	    	    </div>
	    	</div>
	    </div>
	    <div class="mian-cont">
	        <ul class="main-shopping" id="J_detail_content">
	        </ul>
	        <div class="main-shopping-tuijian">
	            <img class="star-img" src="/images/custom_search_index/star_03.png" alt=""/>
	            <span>商品推荐</span>
	            <ul class="main-shopping-tuijian-list" id="J_fixed_contain">
	            </ul>
	        </div>
	    </div>
	<div class="footer text-right" id="J_page_footer"></div>
	</div>
	<!-- 错误页面 -->
	<div class="error-contain hidden">
		<div class="error-body">
			<div class="error-only hidden error-goods">
				<img src="/images/custom_search_index/empty1.png">
				<div class="error-describe">
					没有找到“<span id="error_goods_name"></span>”相关商品
				</div>
			</div>
			<div class="error-all error-goods">
				<img src="/images/custom_search_index/empty2.png">
				<div class="error-describe">
					没有找到“<span id="error_all_name"></span>”相关分类和商品
				</div>
			</div>
			<div class="error-tuijian">
				<img class="error-title" src="/images/custom_search_index/title.jpg">
				<ul class="error-goods" id="J_error_goods">
					
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- 商品详情 -->
<script type="text/template" id="J_detail_list">
	{@each _ as it }
		<li class="img-list" title="${it.description}" data-id="${it.id}">
			<a target="_blank" href="/product?id=${it.id}">
				<img class="max-png" src="${it.big_images[0]}?x-oss-process=image/resize,w_210,h_210,limit_1,m_lfit">
			</a>
			<div class="main-shopping-thumbnail">
					<img class="prev-img" src="/images/custom_search_index/left.png">
						<div class="min-contain moreimg">
						<ul>
							{@each it.big_images as item,index}
								{@if index == 0}
								<li class="active">
									<img class="min-png" src="${item}?x-oss-process=image/resize,w_36,h_36,limit_1,m_lfit" data-src="${item}?x-oss-process=image/resize,w_210,h_210,limit_1,m_lfit">
								</li>
								{@else}
								<li>
									<img class="min-png" src="${item}?x-oss-process=image/resize,w_36,h_36,limit_1,m_lfit" data-src="${item}?x-oss-process=image/resize,w_210,h_210,limit_1,m_lfit">
								</li>
								{@/if}
							{@/each}
						</ul>
					</div>
					<img class="next-img" src="/images/custom_search_index/right.png">
			</div>
			<p class="main-shopping-price">
				￥${it.price.min}
			</p>
			<p class="main-shopping-describe">
				${it.title}
			</p>
			<p class="main-shopping-category">
				所属分类：${it.category}
			</p>
		</li>
	{@/each}
</script>
<!-- 固定商品 -->
<script type="text/template" id="J_fixed_goods">
	{@each _ as it}
		<li title="${it.title}">
			<a target="_blank" href="/product?id=${it.id}">
				<img src="${it.main_image}?x-oss-process=image/resize,w_180,h_180,limit_1,m_lfit">
				<p class="main-shopping-describe">
				${it.title}
				</p>
			</a>
		</li>
	{@/each}
</script>

<script type="text/template" id="B_fixed_goods">
	{@each _ as it}
		<li title="${it.title}" data-id="${it.id}">
			<a target="_blank" href="/product?id=${it.id}">
				<img src="${it.main_image}?x-oss-process=image/resize,w_180,h_180,limit_1,m_lfit">
				<p class="main-shopping-price">
				￥${it.price.min}
				</p>
				<p class="main-shopping-describe">
				${it.title}
				</p>
			</a>
		</li>
	{@/each}
</script>
<script type="text/template" id="J_sort_list">
	{@each _ as it}
		<span data-id="${it.id}">${it.title}</span>
	{@/each}
</script>
<script type="text/template" id="J_tpl_list">
	{@each _ as it}
		<li>
			<a href="javascript:;">
				{@if it.header_img == ''}
					<img src="http://images.9daye.com.cn/s_28/ffc9ef976fadd766eca4726f209bdfe380e5c8904.png?x-oss-process=image/resize,w_76,h_76,limit_1,m_lfit
" data-id="${it.id}">
				{@else}
					<img src="${it.header_img}" data-id="${it.id}">
				{@/if}
			</a>
		</li>
	{@/each}
</script>
