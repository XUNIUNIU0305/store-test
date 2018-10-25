<?php
$this->params = ['js' => 'js/refund_detail.js', 'css' => 'css/refund_detail.css'];

$this->title = '九大爷平台 - 账户中心 - 退换单详情';
?>

<div class="container-fluid apx-cart-container apx-after-sales-detail bg-muted">
    <div class="row">
        <div class="panel">
            <div class="container">
                <div class="row">
                    <div class="h3 title">售后详情</div>
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
                        </div>
                        <div class="panel-body">
                            <div class="h2">当前状态：<span class="J_refund_status"></span></div>
                            <ul class="list-unstyled apx-after-sales-timeline J_refund_log"></ul>
                            <div class="apx-after-sales-logistics-box clearfix">
                                <div class="h4">
                                    本次售后申请取消原因：
                                </div>
                                <ul class="list-unstyled">
                                    <li><span class="J_cancel_reason"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--审核中-->
        <div class="panel hidden" id="J_new_apply">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--审核通过（退货）-->
        <div class="panel hidden" id="J_audit_pass">
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
                                    <i class="glyphicon glyphicon-ok"></i> <span class="J_pass_title">请将申请商品寄回以下地址并提交快递信息，收到后为您退款</span>
                                </div>
                                <ul class="list-unstyled">
                                    <li>地址：<span class="J_supply_address"></span></li>
                                    <li>收件人：<span class="J_supply_name"></span></li>
                                    <li>手机号：<span class="J_supply_mobile"></span></li>
                                    <li>固定电话：<span class="J_supply_telephone"></span></li>
                                </ul>
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="logisticName">物流公司：</label>
                                        <div class="choose-express" style="display: inline-block; width: 200px;">
                                          <input type="text" />
                                          <select class="form-control">
                                            <option>选择物流</option>
                                          </select>
                                          <div></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">快递单号：</label>
                                        <div style="display: inline-block; width: 400px;">
                                          <input type="text" class="form-control" id="J_express_no">
                                        </div>
                                    </div>
                                </form>
                                <a class="btn btn-danger btn-block btn-lg" id="J_submit_msg">提交快递信息</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--退货中-->
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
                            <div class="h2">当前状态：<span class="J_refund_status"></span></div>
                            <ul class="list-unstyled apx-after-sales-timeline J_refund_log"></ul>
                            <div class="apx-after-sales-logistics-box clearfix">
                                <div class="h4">
                                    <i class="glyphicon glyphicon-ok"></i> 您寄出的商品还在路上，收到后为您做相应处理
                                </div>
                                <ul class="list-unstyled">
                                    <li><strong class="text-danger">历史寄单信息～</strong></li>
                                    <li>地址：<span class="J_supply_address"></span></li>
                                    <li>收件人：<span class="J_supply_name"></span></li>
                                    <li>手机号：<span class="J_supply_mobile"></span></li>
                                    <li>固定电话：<span class="J_supply_telephone"></span></li>
                                    <li>快递公司：<span class="J_express_name"></span></li>
                                    <li>快递单号：<span class="J_express_no"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--确认退款-->
        <div class="panel hidden" id="J_refund_admin">
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
                                    <i class="glyphicon glyphicon-ok"></i> 厂家已收到您的商品，客服确认后为您做相应处理
                                </div>
                                <ul class="list-unstyled">
                                    <li><strong class="text-danger">历史寄单信息～</strong></li>
                                    <li>地址：<span class="J_supply_address"></span></li>
                                    <li>收件人：<span class="J_supply_name"></span></li>
                                    <li>手机号：<span class="J_supply_mobile"></span></li>
                                    <li>固定电话：<span class="J_supply_telephone"></span></li>
                                    <li>快递公司：<span class="J_express_name"></span></li>
                                    <li>快递单号：<span class="J_express_no"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--已退款-->
        <div class="panel hidden" id="J_refund_money">
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
                                    <i class="glyphicon glyphicon-ok"></i> 您的货款已退回到您的九大爷账户
                                </div>
                                <ul class="list-unstyled">
                                    <li><strong class="text-danger">历史寄单信息～</strong></li>
                                    <li>地址：<span class="J_supply_address"></span></li>
                                    <li>收件人：<span class="J_supply_name"></span></li>
                                    <li>手机号：<span class="J_supply_mobile"></span></li>
                                    <li>固定电话：<span class="J_supply_telephone"></span></li>
                                    <li>快递公司：<span class="J_express_name"></span></li>
                                    <li>快递单号：<span class="J_express_no"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--换货中-->
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
                                    <i class="glyphicon glyphicon-ok"></i> 收到换货后请确认换货
                                </div>
                                <ul class="list-unstyled">
                                    <li><strong class="text-danger">寄单信息～</strong></li>
                                    <li>地址：<span class="J_customer_address"></span></li>
                                    <li>收件人：<span class="J_customer_name"></span></li>
                                    <li>手机号：<span class="J_customer_mobile"></span></li>
                                    <li>快递公司：<span class="J_express_name"></span></li>
                                    <li>快递单号：<span class="J_express_no"></span></li>
                                </ul>
                                <a class="btn btn-danger btn-block btn-lg" id="J_sure_exchange">确认换货</a>
                            </div>
                        </div>
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
                                                    <a href="${img.path}" data-lightbox="unique-mark0"><img src="${img.path}"></a>
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
                                    <a href="" target="_blank"><img src="" class="img-responsive J_refund_img"></a>
                                </td>
                                <td class="clearfix">
                                    <div class="col-xs-6">
                                        <a href="#" target="_blank" class="J_refund_title"></a>
                                    </div>
                                    <ul class="col-xs-6 list-unstyled J_refund_attrs">

                                    </ul>
                                </td>
                                <td>¥ <span class="J_refund_price"></span></td>
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
        <!--关联订单信息-->
        <div class="panel">
            <div class="container">
                <div class="row">
                    <div class="h4 title">关联订单信息</div>
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
                        <tbody id="J_order_products">
                            <script type="text/template" id="J_tpl_list">
                                {@each items as it}
                                    <tr class="apx-cart-product">
                                        <td></td>
                                        <td>
                                            <a href="/product?id=${it.product_id}" target="_blank"><img src="${it.image}" class="img-responsive"></a>
                                        </td>
                                        <td class="clearfix">
                                            <div class="col-xs-6">
                                                <a href="/product?id=${it.product_id}" target="_blank">${it.title}</a>
                                            </div>
                                            <ul class="col-xs-6 list-unstyled">
                                                {@each it.attributes as attr}
                                                    <li>${attr.attribute}：${attr.option}</li>
                                                {@/each}
                                            </ul>
                                        </td>
                                        <td>¥ ${it.price}</td>
                                        <td>${it.count}</td>
                                        <td>
                                            <strong>¥ ${it.total_fee|price_build}</strong>
                                        </td>
                                    </tr>
                                {@/each}
                                <!-- 总结 -->
                                {@each total_msg as it}
                                    <tr class="apx-cart-product">
                                        <td></td>
                                        <td colspan="6">
                                            <div class="apx-order-total text-right">
                                                <ul class="list-unstyled">
                                                    <li class="h4">共${it.count}样商品 总价：
                                                        <div class="pull-right">¥ ${it.price|price_build}</div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                {@/each}
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
