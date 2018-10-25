<?php
use custom\modules\temp\assets\EmptyAsset;

$this->params = ['css' => 'css/exchange.css', 'js' => 'js/exchange.js'];
EmptyAsset::register($this)->addFiles($this);
?>
<div class="top-title">
    领水券列表
</div>
<div class="row coupon-water-container" id="J_exchange_list">
	<script type="text/template" id="J_tpl_list">
		{@each list as it}
			{@if it.used === true}
				<div class="col-xs-4 coupon-water coupon-water-used">
			{@else}
				<div class="col-xs-4 coupon-water">
			{@/if}
	            <div class="coupon-water-inner">
	                <div class="img-container">
	                    <div class="info">
	                        <strong>1</strong>
	                        <small>元</small>
	                        <span>领水券</span>
	                    </div>
	                </div>
	                <div class="content">
	                    <p>&nbsp;&nbsp;&nbsp;领取码：${it.pick_id}</p>
	                    <p>购买时间：${it.pay_time}</p>
	                    {@if it.used === true}
	                    	<p>使用时间：${it.pick_time}</p>
	                    {@else}
	                    	<p title="${it.area}">使用地点：${it.area}</p>
	                    {@/if}
	                </div>
	            </div>
	        </div>
		{@/each}
	</script>
</div>
