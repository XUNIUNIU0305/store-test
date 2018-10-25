<?php
$this->params = ['js' => 'js/accountconsumption.js', 'css' => 'css/accountconsumption.css'];
?>

<div class="admin-main-wrap">
    <div class="admin-ranking-container">
        <!--nav-->
        <ul class="nav nav-tabs datepicker-nav">
            <li><strong>账号日消费排名</strong></li>
            <li class="pull-right">
                <div class="query_time in">
                    <div class="input-group">
                        <input type="text" class="form-control date-picker J_search_time" value="">
                        <span class="input-group-btn J_date_btn">
                                <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                    </div>
                    <button class="btn btn-default" id="J_search_btn"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </li>
        </ul>
        <div class="row">
            <div class="col-xs-12">
                <div class="admin-management-panel">
                    <div class="header">
                        <div class="col-xs-1">名次</div>
                        <div class="col-xs-1">订单数</div>
                        <div class="col-xs-1">消费金额</div>
                        <div class="col-xs-2">账户ID</div>
                        <div class="col-xs-2">店铺名</div>
                        <div class="col-xs-2">门店所属区域</div>
                        <div class="col-xs-3">所属运营商</div>
                    </div>
                    <div class="iscroll_container with-header with-footer">
                        <ul class="list-unstyled dashed-split" id="J_rank_list">
                            <script type="text/template" id="J_tpl_list">
                                {@each _ as it, index}
                                    <li>
                                        <div class="col-xs-1">${index - 0 + 1}</div>
                                        <div class="col-xs-1">${it.COUNT}单</div>
                                        <div class="col-xs-1">¥${it.SUM}</div>
                                        <div class="col-xs-2">${it.account}</div>
                                        <div class="col-xs-2">${it.shop_name}</div>
                                        <div class="col-xs-2">${it.business_area}</div>
                                        <div class="col-xs-3">${it.area}</div>
                                    </li>
                                {@/each}
                            </script>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
