<?php
$this->params = ['css' => 'css/exchange.css', 'js' => 'js/exchange.js'];
?>
<div class="business-main-wrap">
    <div class="business-water-container">
        <!--search bar-->
        <div class="business-water-form">
            <!-- /input-group -->
            <div class="input-group">
                <input type="text" class="form-control" id="J_exchange_id" placeholder="请输入兑换码">
                <span class="input-group-btn">
                    <button class="btn btn-default" id="J_search_btn" type="button"><i class="glyphicon glyphicon-search"></i></button>
                </span>
            </div>
            <a class="water-strategy">查看攻略</a>
        </div>
        <!--info-->
        <div class="business-water-info hidden">
            <div class="h3">领水信息</div>
            <ul class="list-unstyled">
                <li class="exchange_info hidden">
                    <i class="cyan">1</i> 领水码：<span id="J_pick_id"></span>
                </li>
                <li class="exchange_info hidden">
                    <i class="red">2</i> 购买时间：<span id="J_pay_time"></span>
                </li>
                <li class="exchange_info hidden">
                    <i class="orange">3</i> 购买人：<span id="J_custom_user"></span>
                </li>
                <!--error-->
                <li class="error"></li>
                
            </ul>
            <button class="btn btn-default exchange_info hidden" id="J_exchange_btn">立即兑换</button>
        </div>
    </div>
</div>
<!-- 攻略 -->
<div class="water-strategy-content hidden">
    <p class="close">X</p>
    <img src="/images/business/water/strategy.jpg">
</div>
<!-- 提示 -->
<div class="apx-modal-business-alert modal fade apx-modal-business-water" id="apxModalBusinessAlert" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            <div class="modal-body">
                <img src="/images/business/water/check.png">兑换成功
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business"  data-dismiss="modal">确认</button>
            </div>
        </div>
    </div>
</div>