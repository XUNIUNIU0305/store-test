<?php
use yii\helpers\Html;

$this->params = ['js' => 'js/product.js', 'css' => 'css/product.css'];
?>


<div class="row apx-edit-product-wrap" id="J_products_box">
	<script type="text/template" id="J_tpl_product">
		{@each product as it, index}
			<div class="col-xs-3  J_id_box" data-id="${it.id}" data-sort="${it.category}" data-status="${it.sale_status}">
		        {@if it.sale_status == 0}
		        	<div class="apx-item apx-item-edit unfinished">
		        {@else if it.sale_status == 1}
		        	<div class="apx-item apx-item-edit">
		        {@else}
		        	<div class="apx-item apx-item-edit disabled">
		        {@/if}
		            <div class="item-pic">
		                <img src="${it.main_image}" alt="" class="img-responsive">
		            </div>
		            <div class="item-cnt clearfix">
		                <div class="item-cnt-detail">
		                    <div class="item-cnt-title">${it.title}</div>
		                    <div class="clearfix">
		                        <div class="pull-left">
		                        	{@if it.price.min == it.price.max}
		                            	<strong title="￥${it.price.max}">¥${it.price.max}</strong>
		                        	{@else}
		                        		<strong title="￥${it.price.min}-${it.price.max}">¥${it.price.min}-${it.price.max}</strong>
		                        	{@/if}
		                        </div>
		                        <div class="pull-right">
		                        	{@if it.sale_status == 0}
		                            	<strong class="J_now_status">状态: 未完成</strong>
		                            {@else if it.sale_status == 1}
		                            	<strong class="J_now_status">状态: 在售</strong>
		                            {@else}
		                            	<strong class="J_now_status">状态: 未售</strong>
		                            {@/if}
		                        </div>
		                    </div>
		                </div>
		                <div class="row btn-row">
		                    <div class="col-xs-4"><a href="javascript:;" class="btn btn-default btn-block J_edit_sort">信息</a></div>
		                    <div class="col-xs-4"><a href="javascript:;" class="btn btn-default btn-block J_alter">价格</a></div>
		                    <div class="col-xs-4"><a href="javascript:void(0)" class="btn btn-default btn-block J_edit_product_order">状态</a></div>
		                    <div class="edit-order-box J_select">
		                        <div class="col-xs-6 form-group-sm">
		                            <select class="form-control J_select_brand">
		                                <option>上架</option>
		                                <option>下架</option>
		                            </select>
		                        </div>
		                        <div class="col-xs-6 J_product_submit"><a href="javascript:;" class="btn btn-sm btn-default btn-block">提交</a></div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		{@/each}
	</script>
</div>
<!-- pagination -->
<div class="text-right J_pagination_box"></div>
