<?php
/**
 * Created by PhpStorm.
 * User: wang li
 * @var $this \yii\web\View
 */

$this->title = '实时数据';
$asset = \business\modules\data\assets\BasicAsset::register($this);
$asset->js[] = 'js/real-time.js';
$asset->css[] = 'css/real-time.css';
?>

<div class="business-main-wrap">
	<div class="business-data-daily">
	    <h2 class="title">
	    	<?= date('Y-m-d')?>
			&nbsp;&nbsp;即时战况
	    </h2>
	    <div class="J_total_loading_box">
	    	<div class="top row">
	    	    <div class="col-xs-4">
	    	        <div class="data-chart has-shadow">
	    	            <div class="chart-box" id="container"></div>
	    	            <div class="detail">
	    	                <p class="h2">总转化率</p>
	    	                <p class="h3">参与过消费的账号数/总注册数=总转化率<br><span id="J_total_num"></span>(个)/<span class="J_custom_num"></span>(个)=<span id="J_total_conversion"></span></p>
	    	            </div>
	    	            <div class="detail">
	    	                <p class="h2">日活跃度</p>
	    	                <p class="h3">当日消费人数/总注册人数=日活跃度<br>
	    	                <span class="J_day_fee"></span>(人)/<span class="J_custom_num"></span>(人)=<span id="J_day_activity"></span></p>
	    	            </div>
	    	        </div>
	    	    </div>
	    	    <div class="col-xs-8 right-box">
	    	        <div class="has-shadow">
	    	            <p class="title">日总金额</p>
	    	            <p class="content"><span id="J_day_total_fee"></span>元</p>
	    	        </div>
	    	        <div class="has-shadow">
	    	            <p class="title">日订单数</p>
	    	            <p class="content"><span id="J_day_order"></span>单</p>
	    	        </div>
	    	        <div class="has-shadow">
	    	            <p class="title">日消费人数</p>
	    	            <p class="content"><span class="J_day_fee"></span>人</p>
	    	        </div>
	    	        <div class="has-shadow">
	    	            <p class="title">实控门店</p>
	    	            <p class="content"><span class="J_custom_num"></span>家</p>
	    	        </div>
	    	    </div>
	    	</div>
	    	<div class="data-middle">
	    	    <div class="has-shadow">
	    	        <p class="title">新增注册门店数</p>
	    	        <p class="content"><span id="J_register_num"></span>家</p>
	    	    </div>
	    	    <div class="has-shadow">
	    	        <p class="title">新增邀请门店数</p>
	    	        <p class="content"><span id="J_code_num"></span>家</p>
	    	    </div>
	    	    <div class="has-shadow">
	    	        <p class="title">客单价</p>
	    	        <p class="content"><span id="J_unit_price"></span>元/单</p>
	    	    </div>
	    	    <div class="has-shadow">
	    	        <p class="title">客单量</p>
	    	        <p class="content"><span id="J_unit_num"></span>单/个</p>
	    	    </div>
	    	</div>
		</div>
		<!-- 热销商品开始 -->
		<div class="data-sort">
			<div class="clearfix">
				<div class="s-title">热销商品</div>
				<div class="col-xs-1 pull-right hidden">
					<a href="">导出排名</a>
				</div>
				<div class="col-xs-2 pull-right select-sort">
					<select class="selectpicker btn-group-xs J_show_select" data-width="80%">
						<option value="12">12</option>
						<option value="24">24</option>
						<option value="36">36</option>
					</select>
				</div>
			</div>
			<div class="sort-items" id="J_hot_list">
				<script type="text/template" id="J_tpl_hot">
					{@each _ as it, index}
						<div class="item">
							<div class="base-info">
								<div class="img">
									<span>${index - 0 + 1}</span>
									<img src="${it.filename}">
								</div>
								<div class="text">
									<div class="t-title">${it.title}</div>
									<div class="sale-price">
										<p class="p-label">销售额：</p>
										<p class="num">${it.total_fee|toFixed}</p>
									</div>
								</div>
							</div>
							<div class="price">
								<a href="" class="invisible">消费详情</a>
							</div>
						</div>
					{@/each}
				</script>
			</div>
		</div>
		<!-- 热销商品结束 -->
		<!-- 门店销售情况开始 -->
		<div class="store-sale">
			<div class="s-title">门店开单情况</div>
			<div class="sub-title">
				<p class="area" id="J_area_box">
					
				</p>
				<span class="btn btn-business pull-right invisible">返回</span>
			</div>
			<div class="sale-container">
				<div class="row invisible">
					<div class="col-xs-10">
						<div class="title-box">
							<p>
								总消费门店数：
								<a href="">100</a>
								家
							</p>
							<p>
								总消费门店数：
								<a href="">100</a>
								家
							</p>
							<p>
								总消费门店数：
								<a href="">100</a>
								家
							</p>
						</div>
					</div>
					<div class="col-xs-2">
						<a href="">导出排名</a>
						<a href="">查看名单</a>
					</div>
				</div>
				<table class="sale-tab">
					<thead>
						<tr>
							<td>序号</td>
							<td>地区</td>
							<td>消费门店数</td>
							<td>消费总额</td>
							<td>未消费门店数</td>
							<td>消费门店占比</td>
						</tr>
					</thead>
					<tbody id="J_store_list">
						<script type="text/template" id="J_store_tpl">
							{@each _ as it, index}
								<tr>
									<td>${index - 0 + 1}</td>
									<td>
										{@if it.area_level < 5}
											<a href="javascript:;" class="J_area_name" data-id=${it.area_id}>${it.area_name}</a>
										{@else}
											${it.area_name}
										{@/if}
									</td>
									<td>
										{#<a href="/leader/custom-list?area_id=${it.area_id}&daily_custom_consumption_count=1">${it.daily_custom_consumption_count}</a>}
										<a href="javascript:;" class="expense-store" data-name="${it.area_name}" data-id="${it.area_id}" data-type="1">${it.daily_custom_consumption_count}</a>
									</td>
									<td>${it.daily_consumption_amount}</td>
									<td>
										{#<a href="/leader/custom-list?area_id=${it.area_id}&daily_custom_consumption_count=0">${it.daily_custom_unconsumption_count}</a>}
										<a href="javascript:;" class="expense-store" data-name="${it.area_name}" data-id="${it.area_id}" data-type="0">${it.daily_custom_unconsumption_count}</a>
									</td>
									<td>${it|proportion}</td>
								</tr>
							{@/each}
						</script>
					</tbody>
				</table>
				<div class="footer text-right" id="J_page_box"></div>
			</div>
		</div>
		<!-- 门店销售情况结束 -->
		<!-- 门店注册情况开始 -->
		<div class="store-register">
			<div class="s-title">门店注册情况</div>
			<div class="sub-title">
				<p class="area" id="J_area_register_box">
					
				</p>
			</div>
			<div class="date-box">
				<label for="">选择时间：</label>
				<input type="text" class="real-time-input" name="" id="J_register_time" value="">
			</div>
			<table class="register-tab">
				<thead>
					<tr>
						<td>序号</td>
						<td>地区</td>
						<td>注册门店数</td>
					</tr>
				</thead>
				<tbody id="J_register_list">
					<script type="text/template" id="J_tpl_register">
						{@each _ as it, index}
						<tr>
							<td>${index - 0 + 1}</td>
							<td>
								{@if it.area_level < 5}
									<a href="javascript:;" class="J_area_name" data-id=${it.area_id}>${it.area_name}</a>
								{@else}
									${it.area_name}
								{@/if}
							</td>
							<td>${it.quantity}</td>
						</tr>
						{@/each}
					</script>
				</tbody>
			</table>
		</div>
		<!-- 门店注册情况结束 -->
	</div>

</div>

<div class="business-store-expense-modal modal fade" tabindex="-1" role="dialog" id="modalStoreExpense">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="tab-box">
                    <table class="sale-tab">
                        <thead>
                            <tr>
                                <td>门店账号</td>
                                <td>门店手机号</td>
                                <td>消费金额</td>
                            </tr>
                        </thead>
                        <tbody id="J_modal_store_list">
							<script type="text/template" id="J_modal_store_tpl">
								{@each _ as it}
									<tr>
										<td><a target="_blank" href="/leader/custom?account=${it.account}">${it.account}</a></td>
										<td>${it.mobile}</td>
										<td>${it.daily_consumption_amount}</td>
									</tr>
								{@/each}
							</script>
                        </tbody>
                    </table>
                </div>
                <div class="footer text-right" id="J_modal_page_box">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>