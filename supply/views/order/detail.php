<?php
$this->params = ['js' => 'js/order-detail.js', 'css' => 'css/order-detail.css'];
?>
<div class="supply-orders-detail-container">

    <div class="panel panel-danger">
        <div class="panel-heading text-right">
            <span class="h4 pull-left">订单号：<span id="J_order_no"></span></span>
            <span>下单时间：<span id="J_create_time"></span></span>
            <span>付款时间：<span id="J_pay_time"></span></span>
            <span>付款方式：<span id="J_pay_ment"></span></span>
        </div>
        <div class="panel-body">
            <!--订单状态-->
            <!--5种状态分别加类名：status-untreated status-shipped status-received status-closed status-cancel-->
             <div class="supply-order-status text-center">
                <div class="pull-left">
                    <strong>当前状态</strong>
                    <h3></h3>
                </div>
                <div class="clearfix">
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="status-icon untreated"></div>
                            <p>未处理</p>
                        </div>
                        <div class="col-xs-3">
                            <div class="status-icon shipped"></div>
                            <p>已发货</p>
                        </div>
                        <div class="col-xs-3">
                            <div class="status-icon received"></div>
                            <p>已收货</p>
                        </div>
                        <div class="col-xs-3">
                            <div class="status-icon closed"></div>
                            <p>已关闭</p>
                        </div>
                    </div>
                </div>
                <div class="content-cancel">
                    <img src="/images/supply_orders/cancel.png" class="img-responsive">
                    <p>这笔订单已经<span>取消</span>了哦</p>
                </div>
            </div> 

            <!--订单时序-->
            <ul class="supply-order-sequence list-unstyled" id="J_time_list">
                <li>
                    <div class="pull-left"><strong>订单处理时间</strong></div>
                    <strong>订单处理事项</strong>
                </li>
                <script type="text/template" id="J_tpl_time">
                    {@each Atime as it, index}
                        {@if index == 0}
                            <li class="actived">
                        {@else}
                            <li>
                        {@/if}
                            <div class="pull-left">${it}</div>
                            ${content[index]}
                        </li>
                    {@/each}
                </script>
            </ul>
        </div>
    </div>

    <div class="panel panel-danger">
        <div class="panel-body">
            <ul class="list-unstyled">
                <li><strong>收货人：</strong><span id="J_consignee"></span></li>
                <li><strong>联系电话：</strong><span id="J_mobile"></span></li>
                <li><strong>收货地址：</strong><span id="J_address"></span></li>
                <li><strong>物流公司：</strong><span id="J_express_name"></span></li>
                <li><strong>物流单号：</strong><span id="J_express_code"></span></li>
            </ul>
        </div>
    </div>

    <!--商品信息-->
    <table class="table apx-cart-bill-table table-fix">
        <!-- 列表头 -->
        <thead class="thead">
            <tr>
                <th width=18>
                </th>
                <th width="100">
                </th>
                <th>商品信息</th>
                <th width="240">备注</th>
                <th width="120">单价</th>
                <th width="110">数量</th>
                <th width="100">金额</th>
            </tr>
        </thead>
        <!-- 内容 -->
        <tbody id="J_items_box">
        	<script type="text/template" id="J_tpl_item">
        		{@each items as it}
					<tr class="apx-cart-product">
		                <td></td>
		                <td>
		                    <img src="${it.image}" class="img-responsive">
		                </td>
		                <td class="clearfix">
		                    <div>
		                        <a href="#">${it.title}</a>
		                    </div>
		                    <ul class="list-inline text-muted">
		                    	{@each it.attributes as attr}
		                        	<li>${attr.attribute}：${attr.option}</li>
		                    	{@/each}
		                    </ul>
		                </td>
		                <td class="text-muted remark">${it.comments}</td>
		                <td>¥ ${it.price}</td>
		                <td>${it.count}</td>
		                <td>
		                    <strong>¥ ${it.total_fee}</strong>
		                </td>
		            </tr>
        		{@/each}
        	</script>
            <!-- 总结 -->
        </tbody>
    </table>
    <div class="supply-order-total text-right">
        <ul class="list-unstyled">
            <li class="h4">
                <p>共<span id="J_count"></span>件商品 &nbsp;
                商品总金额:&nbsp; <span class="pull-right style-control">¥<span class="J_items_fee"></span></span></p>
                <p>优惠金额：<span class="pull-right">¥<span id="J_coupon_price"></span></span></p>
                <p>退款金额：<span class="pull-right">¥<span id="J_refund_price"></span></span></p>
                <p><strong>实付金额</strong>：<strong class="pull-right text-danger">¥<span class="J_total_fee"></span></strong></p>
            </li>
            <!-- <button class='btn btn-danger' onclick="window.history.back()">返回</button> -->
        </ul>
    </div>
</div>