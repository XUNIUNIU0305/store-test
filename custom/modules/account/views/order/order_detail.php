<?php
$this->params = ['js' => 'js/order_detail.js', 'css' => 'css/order_detail.css'];

$this->title = '九大爷平台 - 订单详情';
?>
<div class="container-fluid apx-cart-container bg-muted">
    <div class="container">
        <div class="row">
            <h3 class="text-danger apx-cart-title"><strong>订单详情</strong></h3>

            <!--订单状态-->
            <!--五种状态分别加类名：status-pay status-ship status-receive status-confirm status-cancel-->
            <div class="apx-order-status text-center J_order_box">
                <div class="pull-left" style="position: relative;">
                    <strong class="J_order_no">订单号：1201234561</strong>
                    <h3></h3>
                    <a href="#" class="btn J_next_btn invisible"></a>
                    <div class="service-click-container">
                        <span onClick="qimoChatClick();">联系客服</span>
                    </div>
                </div>
                <div class="clearfix">
                    <div class="text-left text-muted J_order_msg">亲，您的订单已生效，现在可以付款了哦！</div>
                    <div class="row">
                        <div class="col-xs-3 J_pay_box">
                            <div class="status-icon pay"></div>
                            <p>待付款</p>
                            <small></small>
                            <small></small>
                        </div>
                        <div class="col-xs-3 J_ship_box">
                            <div class="status-icon ship"></div>
                            <p>待发货</p>
                            <small></small>
                            <small></small>
                        </div>
                        <div class="col-xs-3 J_receive_box">
                            <div class="status-icon receive"></div>
                            <p>待收货</p>
                            <small></small>
                            <small></small>
                        </div>
                        <div class="col-xs-3 J_confirm_box">
                            <div class="status-icon confirm"></div>
                            <p>确认收货</p>
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
                    {@each Atime as it, index}
                        <li>
                            <div class="pull-left">${it}</div>
                            ${content[index]}
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
                                    <span class="pull-left">收件人:</span>
                                    <div class="J_consignee"></div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left">地址:</span>
                                    <div class="J_address"></div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left">手机号码:</span>
                                    <div class="J_mobile"></div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-3">
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
                                    <span class="pull-left">付款方式:</span>
                                    <div id="J_pay_ment"></div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left ">商品总额:</span>
                                    <div class="J_order_price"></div>
                                </li>
                                <li class="clearfix">
                                    <span class="pull-left ">优惠总额:</span>
                                    <div class="J_coupon_price"></div>
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
                                    <div class="J_coupon_price"></div>
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
                        {@each items as it}
                            <tr class="apx-cart-product">
                                <td></td>
                                <td>
                                    <a href="/product?id=${it.product_id}"><img src="${it.image}" class="img-responsive"></a>
                                </td>
                                <td class="clearfix">
                                    <div class="col-xs-6">
                                        <a href="/product?id=${it.product_id}">${it.title}</a>
                                    </div>
                                    <ul class="col-xs-6 list-unstyled">
                                        {@each it.attributes as attr}
                                            <li>${attr.attribute}：${attr.option}</li>
                                        {@/each}
                                    </ul>
                                </td>
                                <td class="remark">${it.comments}</td>
                                <td>¥ ${it.price|pric_build}</td>
                                <td>${it.count}</td>
                                <td>
                                    <strong>¥ ${(it.price*it.count)|pric_build}</strong>
                                </td>
                            </tr>
                        {@/each}
                        <tr class="apx-cart-product">
                            <td></td>
                            <td colspan="6">
                                <div class="apx-order-total text-right">
                                    <ul class="list-unstyled">
                                        <li>共${items.length}个商品 金额: <div class="pull-right">¥ ${items_fee|pric_build}</div></li>
                                        <li>优惠金额: <div class="pull-right">¥ ${coupon_rmb|pric_build}</div></li>
                                        <li>已退款金额: <div class="pull-right">¥ ${refund_rmb|pric_build}</div></li>
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
