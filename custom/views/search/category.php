<?php
$this->params = ['js' => 'js/search-category.js', 'css' => 'css/search-category.css'];
$this->title = '九大爷平台 - 分类';

?>

<div class="search-container">
	<header>
		<div class="line">
			<span>商品分类</span>
			<span>></span>
			<span id="sort_name"></span>
		</div>
		<div class="Screening-condition-content">
			<div class="screen-condition-all" id="screen-condition">
				<span class="sctitle">筛选条件：</span>
				<div class="screen-condition-detail">
				</div>
				<div class="clear">清空条件</div>
			</div>
			<div id="J_sort_content" class="d_sort_content">
			</div>
		</div>
		<div class="slideUp">
			<span id="slidetext">显示更多</span>
			<img src="/images/custom_category/pulldown.png" id="slideimg">
		</div>
	</header>
	<div class="search-contain">
		<div class="main">
		    <div class="mian-cont">
				<div class="main-head">
					<div class="sort_price">
						<span class="price_title J_sort_btn" data-type="max_price">价格</span>
						<div class="sort_price_img" data-id="no_chose">
							<img id="price_up" class="top-img" data-id="2" src="/images/custom_category/up.png">
							<img id="price_down" class="down-img" data-id="2" src="/images/custom_category/down.png">
						</div>
					</div>
					<div class="sales-volume">
						<span class="sales_title J_sort_btn" data-type="sales">销量</span>
						<div class="sales_img" data-id="no_ch">
							<img id="sales_up" class="top-img" data-id="2" src="/images/custom_category/up.png">
							<img id="sales_down" class="down-img" data-id="2" src="/images/custom_category/down.png">
						</div>
					</div>
					<div class="detail-page">
						<img id="prev-page" src="/images/custom_category/left.png">
						<div class="page-count">
						<span id="page-now"></span>
						<span>/</span>
						<span id="page-max"></span>
						</div>
						<img id='next-page' src="/images/custom_category/right.png">
					</div>
				</div>
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
			<div class="error-tuijian">
				<img class="error-title" src="/images/custom_search_index/title.jpg">
				<ul class="error-goods clearfix" id="J_error_goods">
					
				</ul>
			</div>
		</div>
	</div>
</div>
<script type="text/template" id="J_sort_list">
	{@each _ as it,index}
		<div class="sCoditionone">
			<span class="sctitle" id="${it.id}">${it.name}：</span>
			<div class="selectwrap">
				<div class="select-list">
					{@each _[index].options as items}
						<div class="select-details" id="${items.id}" data-id="false">
							<span>${items.name}</span>
						</div>
					{@/each}
				</div>
				<div class="more-list">
					<div class="more-select-list">
						{@each _[index].options as items}
							<div class="m-select-details" id="${items.id}" data-id="false">
								<div class="chosecheck" data-id="false"></div>
								<span>${items.name}</span>
							</div>
						{@/each}
					</div>
					<div class="confire">
						<div class="conbtn">确定</div>
						<div class="calcon">取消</div>
					</div>
				</div>
			</div>
			<div class="more-chose" data-id="close">+多选</div>	
		</div>
	{@/each}	
</script>
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