<?php
$this->params = ['js' => 'js/order.js', 'css' => 'css/order.css'];

$this->title = '九大爷平台 - 账户中心 - 订单';
?>
<!-- <ul class="nav nav-tabs apx-add-order-nav" role="tablist">
    <li role="presentation" class="active">
        <a href="#tab_all" role="tab" data-toggle="tab">所有订单
        </a>
    </li>
    <li role="presentation">
        <a href="#tab_pay" role="tab" data-toggle="tab">待付款
        </a>
    </li>
    <li role="presentation">
        <a href="#tab_ship" role="tab" data-toggle="tab">待发货
        </a>
    </li>
    <li role="presentation">
        <a href="#tab_receive" role="tab" data-toggle="tab">待收获
        </a>
    </li>
    <li role="presentation">
        <a href="#tab_confirm" role="tab" data-toggle="tab">确认收货
        </a>
    </li>
</ul> -->
<div class="top-title">订单列表</div>
<div class="br"></div>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in active" id="tab_all">
       <table class="table table-fix apx-acc-table-title text-center">
            <td width="80"><a href="javascript:;">按时间 <i class="glyphicon glyphicon-arrow-up"></i></a></td>
            <td width="346">商品</td>
            <td width="83">状态</td>
            <td width="95">单价</td>
            <td width="66">数量</td>
            <td width="156">总金额</td>
            <td width="103">操作</td>
        </table>
        <div id="J_order_list" class="acc-order-list">
        </div>
    </div>
<!--     <div role="tabpanel" class="tab-pane fade" id="tab_pay">null</div>
    <div role="tabpanel" class="tab-pane fade" id="tab_ship">null</div>
    <div role="tabpanel" class="tab-pane fade" id="tab_receive">null</div>
    <div role="tabpanel" class="tab-pane fade" id="tab_confirm">null</div> -->
</div>
<!-- pagination -->
<div class="text-right" id="J_page_list">
</div>
