<?php
$this->params = ['js' => 'js/gpubs-order-detail.js', 'css' => 'css/gpubs-order-detail.css'];
$this->title = '';
?>
<div class="container-fluid apx-cart-container bg-muted">
    <div class="container">
        <div class="row">
            <h3 class="text-danger apx-cart-title"><strong>订单详情</strong></h3>

            <!--订单状态-->
            <!--五种状态分别加类名：status-pay status-ship status-receive status-confirm status-cancel-->
            <div class="apx-order-status text-center J_order_box">
                <div class="pull-left">
                    <strong class="J_order_no">订单号：</strong>
                    <h5 class="J_group_no"></h5>
                    <h4 class="J_pick_num"></h4>
                    <h4 class="hidden">提货码：<span class="J_picking_up_number"></span></h4>
                </div>
                <div class="clearfix">
                    <div class="text-left text-muted J_order_msg"></div>
                    <div class="row">
                        <div class="col-xs-3 J_pay_box">
                            <div class="status-icon pay"></div>
                            <p>拼购中</p>
                            <small></small>
                            <small></small>
                        </div>
                        <div class="col-xs-3 J_ship_box">
                            <div class="status-icon ship"></div>
                            <p>未提货</p>
                            <small></small>
                            <small></small>
                        </div>
                        <div class="col-xs-3 J_receive_box">
                            <div class="status-icon receive"></div>
                            <p>部分提货</p>
                            <small></small>
                            <small></small>
                        </div>
                        <div class="col-xs-3 J_confirm_box">
                            <div class="status-icon confirm"></div>
                            <p>全部提货</p>
                            <small></small>
                            <small></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--订单时序-->
            <ul class="apx-order-sequence list-unstyled J_time_list">
                <li>
                    <div class="pull-left"><strong>订单处理时间</strong></div>
                    <strong>订单处理事项</strong>
                </li>
                <script type="text/template" id="J_tpl_time">
                    {@each _ as it}
                        <li>
                            <div class="pull-left">${it.picking_up_datetime}</div>
                            提货${it.quantity_to_pick}件
                        </li>
                    {@/each}
                </script>
            </ul>

            <!-- 订单轮播 -->
            <div id="apx-order-carousel" class="carousel slide apx-order-carousel" data-ride="carousel">
                <!-- Indicators -->
                 <ol class="carousel-indicators">
                    <li data-target="#apx-order-carousel" data-slide-to="0" class="active"></li>
                    <!-- <li data-target="#apx-order-carousel" data-slide-to="1"></li> -->
                </ol> 
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item clearfix active">
                        <div class="col-xs-3">
                            <strong>收货人信息</strong>
                            <ul class="list-unstyled">
                                <li class="clearfix">
                                    <span class="pull-left">联系人:</span>
                                    <div class="J_consignee"></div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left">自提地址:</span>
                                    <div class="J_address"></div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left">联系人手机:</span>
                                    <div class="J_mobile"></div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-3 hidden">
                            <strong>配送信息</strong>
                            <ul class="list-unstyled">
                                <li class="clearfix">
                                    <span class="pull-left">快递公司:</span>
                                    <div id="J_express_name"></div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left">快递单号:</span>
                                    <div id="J_express_code"></div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left">发货日期:</span>
                                    <div class="J_deliver_time"></div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-3">
                            <strong>付款信息</strong>
                            <ul class="list-unstyled">
                                <li class="clearfix">
                                    <span class="pull-left ">商品总额:</span>
                                    <div class="J_order_price"></div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left J_practical_price">实付金额:</span>
                                    <div class="J_end_price"></div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left ">已退金额:</span>
                                    <div class="J_refund_price"></div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-3">
                            <strong>优惠信息</strong>
                            <ul class="list-unstyled">
                                <li class="clearfix">
                                    <span class="pull-left ">优惠券名：</span>
                                    <div class="J_coupon_name">无</div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left ">对应品牌：</span>
                                    <div class="J_coupon_brand">无</div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left ">满足金额：</span>
                                    <div class="J_limit_price">无</div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left ">优惠金额：</span>
                                    <div class="J_coupon_price">无</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#apx-order-carousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#apx-order-carousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a> 
            </div>
            
            <!--商品信息-->
            <table class="table apx-cart-bill-table">
                <!-- 列表头 -->
                <thead class="thead">
                    <tr>
                        <th width="32">
                        </th>
                        <th width="128">
                        </th>
                        <th>商品信息</th>
                        <th width="160">备注</th>
                        <th width="140">单价</th>
                        <th width="100">数量</th>
                        <th width="140">金额</th>
                    </tr>
                </thead>
                <!-- 内容 -->
                <tbody class="J_product_box">
                    <script type="text/template" id="J_tpl_detail">
                        <tr class="apx-cart-product">
                            <td></td>
                            <td>
                                <a ><img src="${image}" class="img-responsive"></a>
                            </td>
                            <td class="clearfix">
                                <div class="col-xs-6">
                                    <a>${title}</a>
                                </div>
                                <ul class="col-xs-6 list-unstyled">
                                    {@each sku as attr}
                                        <li>${attr.name}：${attr.selectedOption.name}</li>
                                    {@/each}
                                </ul>
                            </td>
                            <td class="remark"></td>
                            <td>¥ ${product_sku_price|pric_build}</td>
                            <td>${quantity}</td>
                            <td>
                                <strong>¥ ${total_fee|pric_build}</strong>
                            </td>
                        </tr>
                        <tr class="apx-cart-product">
                            <td></td>
                            <td colspan="6">
                                <div class="apx-order-total text-right">
                                    <ul class="list-unstyled">
                                        <li>共${quantity}个商品 金额: <div class="pull-right">¥ ${total_fee|pric_build}</div></li>
                                        <li class="h4">
                                            实际订单金额: <strong class="pull-right">¥ ${total_fee|pric_build}</strong>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </script>
                </tbody>
            </table>
        </div>
    </div>
</div>

