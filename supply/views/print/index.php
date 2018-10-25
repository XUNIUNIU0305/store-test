<?php
$this->params = ['js' => 'js/print.js', 'css' => 'css/print.css'];
?>
<div class="supply-order-print">
	<div class="print-head">
		<img src="/images/business/wechat.png">
		<span class="wechat">微信扫一扫</span>
		<p>九大爷平台商品发货单</p>
		<small>http://www.9daye.com.cn</small>
		<h3>订单编号：<span id="J_order_id"></span></h3>
	</div>
	<div class="br"></div>
	<div class="print-content clearfix">
		<p class="pull-left left-content">买家：<span id="J_customer_account"></span></p>
		<p class="pull-left">运单号：<span id="J_express_number"></span></p>
		<p class="pull-left left-content">买家电话：<span id="J_mobile"></span></p>
		<p class="pull-left">付款时间：<span id="J_pay_time"></span></p>
		<p class="pull-left left-content">收货地址：<span id="J_address"></span></p>
		<p class="pull-left">下单时间：<span id="J_create_time"></span></p>
	</div>
	<div class="table-box">
		<table>
			<thead>
				<tr>
					<th>序号</th>
					<th>商品名称</th>
					<th>属性</th>
					<th>数量</th>
					<th>备注</th>
				</tr>
			</thead>
			<tbody id="J_table_box">
				<script type="text/template" id="J_tpl_table">
					{@each items as it, index}
						<tr>
							<td>${index - 0 + 1}</td>
							<td>${it.title}</td>
							<td>${it.attributes|attributes}</td>
							<td>${it.count}</td>
							<td>${it.comments}</td>
						</tr>
					{@/each}
				</script>
			</tbody>
		</table>
	</div>
	<div class="print-time text-right">打印时间：<span class="time-box"></span></div>
</div>
