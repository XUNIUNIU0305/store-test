<?php
$this->params = ['js' => 'js/refund_detail.js', 'css' => 'css/refund_detail.css'];

$this->title = '九大爷平台 - 账户中心 - 退换货详情';
?>

<div class="container-fluid apx-cart-container apx-after-sales-detail bg-muted">
    <div class="row">
        <div class="panel">
            <div class="container">
                <div class="row">
                    <div class="h3 title">退换货详情</div>
                </div>
            </div>
        </div>

        <!--已取消-->
        <div class="panel hidden" id="J_refund_cancel">
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="h4">售后单号：<span class="J_refund_no"></span></span>
                            <small>关联订单号：<span class="J_order_no"></span></small>
                            <small>退款金额：<span class="high-lighted J_refund_price"></span></small>
                        </div>
                        <div class="panel-body">
                            <div class="h2">当前状态：<span class="J_refund_status"></span></div>
                            <ul class="list-unstyled apx-after-sales-timeline J_refund_log"></ul>
                            <div class="apx-after-sales-logistics-box clearfix">
                                <div class="h4">
                                    <span class="J_tip_title">本次售后取消原因：</span>
                                </div>
                                <ul class="list-unstyled">
                                    <li class="J_refund_reason"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--待审核（换货）-->
        <div class="panel hidden" id="J_new_refund">
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="h4">售后单号：<span class="J_refund_no"></span></span>
                            <small>关联订单号：<span class="J_order_no"></span></small>
                            <small>退款金额：<span class="high-lighted J_refund_price"></span></small>
                        </div>
                        <div class="panel-body">
                            <div class="h2">当前状态：<span class="J_refund_status"></span></div>
                            <ul class="list-unstyled apx-after-sales-timeline J_refund_log"></ul>
                            <div class="apx-after-sales-logistics-box clearfix">
                                <div class="h4">
                                    <i class="glyphicon glyphicon-ok"></i><span class="J_tip_title">同意对方换货申请，收到货后立即给对方发货</span>
                                </div>
                                <ul class="list-unstyled">
                                    <li class="J_refund_name"></li>
                                    <li class="J_refund_mobile"></li>
                                    <li class="J_refund_address"></li>
                                    <li>&nbsp;</li>
                                    <li>&nbsp;</li>
                                </ul>
                                <button class="btn btn-danger btn-block btn-lg" id="J_sure_order">确认</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--已过审-->
        <div class="panel hidden" id="J_refund_pass">
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="h4">售后单号：<span class="J_refund_no"></span></span>
                            <small>关联订单号：<span class="J_order_no"></span></small>
                            <small class="hidden refund_box">退款金额：<span class="high-lighted J_refund_price"></span></small>
                        </div>
                        <div class="panel-body">
                            <div class="h2">当前状态：<span class="J_refund_status"></span></div>
                            <ul class="list-unstyled apx-after-sales-timeline J_refund_log"></ul>
                            <div class="apx-after-sales-logistics-box clearfix">
                                <div class="h4">
                                    <i class="glyphicon glyphicon-ok"></i> 此售后请求已通过审核，请耐心等待买家退回商品。
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--退回中（换货）-->
        <div class="panel hidden" id="J_exchange_underway">
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="h4">售后单号：<span class="J_refund_no"></span></span>
                            <small>关联订单号：<span class="J_order_no"></span></small>
                        </div>
                        <div class="panel-body">
                            <div class="h2">当前状态：<span class="J_refund_status"></span></div>
                            <ul class="list-unstyled apx-after-sales-timeline J_refund_log"></ul>
                            <div class="apx-after-sales-logistics-box clearfix">
                                <div class="h4">
                                    <i class="glyphicon glyphicon-ok"></i> 确认收到对方寄来的货物，并发出相应货物
                                </div>
                                <ul class="list-unstyled">
                                    <li class="h3">退换信息</li>
                                    <li class="J_refund_name"></li>
                                    <li class="J_refund_mobile"></li>
                                    <li class="J_refund_telephone"></li>
                                    <li class="J_refund_address"></li>
                                    <li class="J_shipping_company"></li>
                                    <li class="J_shipping_code"></li>
                                    <li class="h3">买家信息</li>
                                    <li>姓名：<span class="J_customer_name"></span></li>
                                    <li>手机号：<span class="J_customer_mobile"></span></li>
                                    <li>地址：<span class="J_customer_address"></span></li>
                                </ul>
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="logisticName" class="col-xs-2">快递公司：</label>
                                        <div class="col-xs-3">
                                            <select class="form-control" id="J_express_list">
                                                <option value="-1">选择物流</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-xs-2">快递单号:</label>
                                        <div class="col-xs-10"><input type="text" class="form-control" id="J_express_no"></div>
                                    </div>
                                </form>
                                <button class="btn btn-danger btn-block btn-lg" id="J_sure_send">提交快递信息</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--已完成（换货）-->
        <div class="panel hidden" id="J_exchange_finished">
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="h4">售后单号：<span class="J_refund_no"></span></span>
                            <small>关联订单号：<span class="J_order_no"></span></small>
                        </div>
                        <div class="panel-body">
                            <div class="h2">当前状态：<span class="J_refund_status"></span></div>
                            <ul class="list-unstyled apx-after-sales-timeline J_refund_log"></ul>
                            <div class="apx-after-sales-logistics-box clearfix">
                                <div class="h4">
                                    <i class="glyphicon glyphicon-ok"></i> 对方已收到货物，换货完成
                                </div>
                                <ul class="list-unstyled">
                                    <li><strong class="text-danger">历史寄单信息～</strong></li>
                                    <li>姓名：<span class="J_customer_name"></span></li>
                                    <li>手机号：<span class="J_customer_mobile"></span></li>
                                    <li>地址：<span class="J_customer_address"></span></li>
                                    <li>快递公司：<span class="J_customer_company"></span></li>
                                    <li>快递单号：<span class="J_customer_code"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--退回中（退货）-->
        <div class="panel hidden" id="J_refund_underway">
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="h4">售后单号：<span class="J_refund_no"></span></span>
                            <small>关联订单号：<span class="J_order_no"></span></small>
                            <small>退款金额：<span class="high-lighted J_refund_price"></span></small>
                        </div>
                        <div class="panel-body">
                            <div class="h2">当前状态：退回中（退货）</div>
                            <ul class="list-unstyled apx-after-sales-timeline J_refund_log"></ul>
                            <div class="apx-after-sales-logistics-box clearfix">
                                <div class="h4">
                                    <i class="glyphicon glyphicon-ok"></i> 确认收货后，将进入最终退款流程，客服确认后，退款金额将会直接退回至客户账户(如有变动，请及时联系客服)
                                </div>
                                <ul class="list-unstyled">
                                    <li class="h3">退换信息</li>
                                    <li class="J_refund_name"></li>
                                    <li class="J_refund_mobile"></li>
                                    <li class="J_refund_telephone"></li>
                                    <li class="J_refund_address"></li>
                                    <li class="J_shipping_company"></li>
                                    <li class="J_shipping_code"></li>
                                    <li class="h3">买家信息</li>
                                    <li>姓名：<span class="J_customer_name"></span></li>
                                    <li>手机号：<span class="J_customer_mobile"></span></li>
                                    <li>地址：<span class="J_customer_address"></span></li>
                                </ul>
                                <button class="btn btn-danger btn-block btn-lg" id="J_sure_refund_over">确认</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--已完成（退货）-->
        <div class="panel hidden" id="J_finished_refund">
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="h4">售后单号：<span class="J_refund_no"></span></span>
                            <small>关联订单号：<span class="J_order_no"></span></small>
                            <small>退款金额：<span class="high-lighted J_refund_price"></span></small>
                        </div>
                        <div class="panel-body">
                            <div class="h2">当前状态：<span class="J_refund_status"></span></div>
                            <ul class="list-unstyled apx-after-sales-timeline J_refund_log"></ul>
                            <div class="apx-after-sales-logistics-box clearfix">
                                <div class="h4">
                                    <i class="glyphicon glyphicon-ok"></i> 对方已收到退款，退货完成
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <!--确认退款（退货）-->
        <div class="panel hidden" id="J_sure_refund">
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="h4">售后单号：<span class="J_refund_no"></span></span>
                            <small>关联订单号：<span class="J_order_no"></span></small>
                            <small>退款金额：<span class="high-lighted J_refund_price"></span></small>
                        </div>
                        <div class="panel-body">
                            <div class="h2">当前状态：<span class="J_refund_status"></span></div>
                            <ul class="list-unstyled apx-after-sales-timeline J_refund_log"></ul>
                            <div class="apx-after-sales-logistics-box clearfix">
                                <div class="h4">
                                    <i class="glyphicon glyphicon-ok"></i> 您已确认退款，客服确认后，退款金额将会直接退回至客户账户
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--换货中-->
        <div class="panel hidden" id="J_finished_underway">
            <div class="container">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="h4">售后单号：<span class="J_refund_no"></span></span>
                            <small>关联订单号：<span class="J_order_no"></span></small>
                        </div>
                        <div class="panel-body">
                            <div class="h2">当前状态：<span class="J_refund_status"></span></div>
                            <ul class="list-unstyled apx-after-sales-timeline J_refund_log"></ul>
                            <div class="apx-after-sales-logistics-box clearfix">
                                <div class="h4">
                                    <i class="glyphicon glyphicon-ok"></i> 更换货物正在运输中
                                </div>
                                <ul class="list-unstyled">
                                    <li><strong class="text-danger">历史寄单信息～</strong></li>
                                    <li>姓名：<span class="J_customer_name"></span></li>
                                    <li>手机号：<span class="J_customer_mobile"></span></li>
                                    <li>地址：<span class="J_customer_address"></span></li>
                                    <li>快递公司：<span class="J_customer_company"></span></li>
                                    <li>快递单号：<span class="J_customer_code"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--关联订单信息-->
        <div class="panel" id="J_associated_msg">
            <div class="container">
                <div class="row">
                    <div class="h4 title">关联订单信息</div>
                    <div class="">
                       <table class="table apx-cart-bill-table">
                        <!-- 列表头 -->
                        <thead class="thead">
                            <tr>
                                <th width="32">
                                </th>
                                <th width="128">
                                </th>
                                <th >商品信息</th>
                                <th width="140">单价</th>
                                <th width="100">数量</th>
                                <th width="140">金额</th>
                                <th width="32"></th>
                            </tr>
                        </thead>
                        <!-- 内容 -->
                        <tbody id="J_orders_info">
                            <!-- 商品 -->
                            <script type="text/template" id="J_tpl_orders">
                                {@each _ as it}
                                    <tr class="apx-cart-product">
                                        <td></td>
                                        <td>
                                            <img src=${it.image} class="img-responsive">
                                        </td>
                                        <td class="clearfix">
                                            <div class="col-xs-6">
                                                <a href="javascript:;">${it.title}</a>
                                            </div>
                                            <ul class="col-xs-6 list-unstyled">
                                                {@each it.attributes as attr}
                                                    <li>${attr.attribute}: ${attr.option}</li>
                                                {@/each}
                                            </ul>
                                        </td>
                                        <td>¥ ${it.price|ju_price}</td>
                                        <td>${it.count}</td>
                                        <td>
                                            <strong>¥ ${it.total_fee|ju_price}</strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                {@/each}
                            </script>
                            <!-- 总结 -->
                            <tr class="apx-cart-product">
                                <td></td>
                                <td colspan="6">
                                    <div class="apx-order-total text-right">
                                        <ul class="list-unstyled">
                                            <li>
                                                共
                                                <span id="J_items_count"></span>
                                                件商品 金额:
                                                <div class="pull-right J_items_fee"></div>
                                            </li>
                                            <li>优惠金额:
                                                <div class="pull-right J_coupon_price">¥0.00</div>
                                            </li>
                                            <li>退款金额:
                                                    <div class="pull-right J_order_refund_price">¥0.00</div>
                                                </li>
                                            <li class="h4">
                                                <strong>实付金额</strong>: <strong class="pull-right J_total_fee"></strong>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                    </div>
                </div>
            </div>
        </div>
        <!--历史提交信息-->
        <div class="panel" id="J_history_msg">
            <div class="container">
                <div class="row">
                    <div class="h4 title">历史提交信息</div>
                    <div class="panel panel-bordered">
                        <div class="panel-heading">
                            <strong>描述：</strong><span class="J_reason"></span>
                        </div>
                        <div class="panel-body">
                            <strong>问题照片</strong>
                            <ul class="list-unstyled gallary J_img_list"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--客服意见-->
        <div class="panel" id="service">
            <div class="container">
                <div class="row">
                    <div class="h4 title">客服意见</div>
                    <div id="J_service_msg">
                        <script type="text/template" id="J_tpl_service">
                            {@each plat_suggestion as it}
                                <div class="panel panel-bordered">
                                    <div class="panel-heading">
                                        <strong>描述：</strong>${it.comments}
                                    </div>
                                    <div class="panel-body">
                                        <strong>补充照片</strong>
                                        <ul class="list-unstyled gallary">
                                            {@each it.images as img}
                                                <li>
                                                    <a href="${img.path}" data-lightbox="unique-mark0" target="_blank"><img src="${img.path}"></a>
                                                </li>
                                            {@/each}
                                        </ul>
                                    </div>
                                </div>
                            {@/each}
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <!--申请商品信息-->
        <div class="panel" id="J_refund_product">
            <div class="container">
                <div class="row">
                    <div class="h4 title">申请商品信息</div>
                    <table class="table apx-cart-bill-table">
                        <!-- 列表头 -->
                        <thead class="thead">
                            <tr>
                                <th width="32">
                                </th>
                                <th width="128">
                                </th>
                                <th>商品信息</th>
                                <th width="140">单价</th>
                                <th width="100">数量</th>
                                <th width="140">金额</th>
                            </tr>
                        </thead>
                        <!-- 内容 -->
                        <tbody>
                            <!-- 商品 -->
                            <tr class="apx-cart-product">
                                <td></td>
                                <td>
                                    <img src="" class="img-responsive J_refund_img">
                                </td>
                                <td class="clearfix">
                                    <div class="col-xs-6">
                                        <span  class="J_refund_title"></span>
                                    </div>
                                    <ul class="col-xs-6 list-unstyled J_refund_attrs">
                                        
                                    </ul>
                                </td>
                                <td>¥ <span class="J_item_price"></span></td>
                                <td class="J_refund_count"></td>
                                <td>
                                    <strong>¥ <span class="J_refund_total"></span></strong>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

</div>