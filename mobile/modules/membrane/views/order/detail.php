<?php
/**
 * @var $this \yii\web\View
 */
use custom\modules\membrane\assets\MembraneAsset;

$this->title = '订单详情';
MembraneAsset::register($this)->addJs('js/detail.js')->addCss('css/detail.css');
?>
<div class="film-order-detail">
	<div class="address">
		<div class="top">
			<p>收货人：<span class="J_name"></span></p>
			<p class="J_mobile"></p>
		</div>
		<p class="detail">地址：<span id="J_detail"></span></p>
	</div>
	<div id="J_pro_box"></div>
	<div class="info">
		<div class="title">货单信息<span id="J_status">已接单</span></div>
		<ul>
			<li>货单号：<span id="J_no"></span></li>
			<li>下单时间：<span id="J_pay_time"></span></li>
			<li>支付方式：<span id="J_payment"></span></li>
		</ul>
	</div>
	<div class="info">
		<div class="title">收货人信息</div>
		<ul>
			<li>姓名：<span class="J_name"></span></li>
			<li>手机号：<span class="J_mobile"></span></li>
			<li>平台账号：<span id="J_account"></span></li>
		</ul>
	</div>
	<div class="total">
		<p>数量：<span id="J_count"></span></p>
		<p>总计：<span class="J_price"></span></p>
		<div class="price">
			<p>实付：<span class="J_price"></span></p>
		</div>
	</div>
</div>
<script type="text/template" id="J_tpl_pro">
	{@each items as it}
		<div class="pro">
			<div class="img">
				<img src="${it.membrane_product_id === 1 ? '/images/film/address/pro-pic.jpg' : '/images/film/address/apex.jpg'}">
			</div>
			<div class="pro-text">
	            <p class="title">${it.name}</p>
	            <p class="attr">
	            	{@each it.attributes as attr}
	            		<span>${attr.block}:${attr.type};</span>
	            	{@/each}
	            </p>
	            <div class="price">¥${it.price}</div>
	            <span class="ammount">1</span>
	        </div>
		</div>
		<div class="remark">备注内容：${it.remark}</div>
	{@/each}
</script>