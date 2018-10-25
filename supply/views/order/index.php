<?php
use yii\helpers\Html;

$this->params = ['js' => 'js/order.js', 'css' => 'css/order.css'];
?>
<div id="dl"></div>
<!-- 退换货tabs -->
<div class="supply-orders-container">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#order_untreated" data-toggle="tab" data-status="1">未处理<span data-title="未处理"></span></a>
        </li>
        <li>
            <a href="#order_treated" data-toggle="tab" data-status="2">已处理<span data-title="已处理"></span></a>
        </li>
        <li>
            <a href="#order_received" data-toggle="tab" data-status="3">已收货<span data-title="已收货"></span></a>
        </li>
        <li>
            <a href="#order_cancel" data-toggle="tab" data-status="4">已取消<span data-title="已取消"></span></a>
        </li>
        <li>
            <a href="#order_close" data-toggle="tab" data-status="5">已关闭<span data-title="已关闭"></span></a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <!-- 未处理 -->
        <div role="tabpanel" class="tab-pane fade active in" id="order_untreated">
            <!-- 订单筛选 -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong class="high-lighted">订单筛选</strong>
                </div>
                <div class="panel-body J_search_box">
                    <div class="row">
                        <div class="col-xs-4 form-horizontal">
                            <div class="form-group">
                                <label for="order_num1" class="col-xs-4">订单号:</label>
                                <div class="col-xs-8">
                                    <input id="order_num1" type="text" class="form-control J_no_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_client1" class="col-xs-4">收货人:</label>
                                <div class="col-xs-8">
                                    <input id="order_client1" type="text" class="form-control J_name_input">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 form-horizontal">
                            <div class="form-group">
                                <label for="buy_acc1" class="col-xs-4">购买帐号:</label>
                                <div class="col-xs-8">
                                    <input id="buy_acc1" type="text" class="form-control J_buy_account">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_address1" class="col-xs-4">收货地址:</label>
                                <div class="col-xs-8">
                                    <input id="order_address1" type="text" class="form-control J_address_input">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 time">
                            <div class="form-group">
                                <label>下单时间：</label>
                                <input type="text" class="form-control date-picker J_create_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_create_end" value="">
                            </div>
                            <div class="form-group">
                                <label>付款时间：</label>
                                <input type="text" class="form-control date-picker J_pay_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_pay_end" value="">
                            </div>
                        </div>
                        <div class="col-xs-12 text-right">
                            <div class="btn-row">
                                <a href="#" class="btn btn-danger J_search_btn">搜索</a>
                                <a href="#" class="btn btn-default" data-toggle="modal" data-target="#exportOrder" data-status="1">导出订单</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="J_order_untreated"></div>
            <!--pagination-->
            <div class="pull-right" id="J_untreated_page"></div>
        </div>
        <!-- 已处理 -->
        <div role="tabpanel" class="tab-pane fade" id="order_treated">
            <!-- 订单筛选 -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong class="high-lighted">订单筛选</strong>
                </div>
                <div class="panel-body J_search_box">
                    <div class="row">
                        <div class="col-xs-4 form-horizontal">
                            <div class="form-group">
                                <label for="order_num2" class="col-xs-4">订单号:</label>
                                <div class="col-xs-8">
                                    <input id="order_num2" type="text" class="form-control J_no_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_client2" class="col-xs-4">收货人:</label>
                                <div class="col-xs-8">
                                    <input id="order_client2" type="text" class="form-control J_name_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_trans1" class="col-xs-4">物流公司:</label>
                                <div class="col-xs-8 choose-express">
                                  <input type="text" />
                                  <select class="form-control"></select>
                                  <div style="left: 15px; width: 186.22px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 form-horizontal">
                            <div class="form-group">
                                <label for="buy_acc2" class="col-xs-4">购买帐号:</label>
                                <div class="col-xs-8">
                                    <input id="buy_acc2" type="text" class="form-control J_buy_account">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_address2" class="col-xs-4">收货地址:</label>
                                <div class="col-xs-8">
                                    <input id="order_address2" type="text" class="form-control J_address_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="trans_code2" class="col-xs-4">物流单号:</label>
                                <div class="col-xs-8">
                                    <input id="trans_code2" type="text" class="form-control J_express_no">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 time">
                            <div class="form-group">
                                <label>下单时间：</label>
                                <input type="text" class="form-control date-picker J_create_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_create_end" value="">
                            </div>
                            <div class="form-group">
                                <label>付款时间：</label>
                                <input type="text" class="form-control date-picker J_pay_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_pay_end" value="">
                            </div>
                            <div class="form-group">
                                <label>发货时间：</label>
                                <input type="text" class="form-control date-picker J_shipments_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_shipments_end" value="">
                            </div>
                        </div>
                        <div class="col-xs-12 text-right">
                            <div class="btn-row">
                                <a href="#" class="btn btn-danger J_search_btn">搜索</a>
                                <a href="#" class="btn btn-default" data-toggle="modal" data-target="#exportOrder" data-status="2">导出订单</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="J_order_treated"></div>
            <!--pagination-->
            <div class="pull-right" id="J_treated_page"></div>
        </div>
        <!-- 已收货 -->
        <div role="tabpanel" class="tab-pane fade" id="order_received">
            <!-- 订单筛选 -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong class="high-lighted">订单筛选</strong>
                </div>
                <div class="panel-body J_search_box">
                    <div class="row">
                        <div class="col-xs-4 form-horizontal">
                            <div class="form-group">
                                <label for="order_num3" class="col-xs-4">订单号:</label>
                                <div class="col-xs-8">
                                    <input id="order_num3" type="text" class="form-control J_no_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_client3" class="col-xs-4">收货人:</label>
                                <div class="col-xs-8">
                                    <input id="order_client3" type="text" class="form-control J_name_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_trans" class="col-xs-4">物流公司:</label>
                                <div class="col-xs-8 choose-express">
                                  <input  type="text" />
                                  <select class="form-control"></select>
                                  <div style="left: 15px; width: 186.22px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 form-horizontal">
                            <div class="form-group">
                                <label for="buy_acc3" class="col-xs-4">购买帐号:</label>
                                <div class="col-xs-8">
                                    <input id="buy_acc3" type="text" class="form-control J_buy_account">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_address3" class="col-xs-4">收货地址:</label>
                                <div class="col-xs-8">
                                    <input id="order_address3" type="text" class="form-control J_address_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="trans_code3" class="col-xs-4">物流单号:</label>
                                <div class="col-xs-8">
                                    <input id="trans_code3" type="text" class="form-control J_express_no">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 time">
                            <div class="form-group">
                                <label>下单时间：</label>
                                <input type="text" class="form-control date-picker J_create_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_create_end" value="">
                            </div>
                            <div class="form-group">
                                <label>付款时间：</label>
                                <input type="text" class="form-control date-picker J_pay_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_pay_end" value="">
                            </div>
                            <div class="form-group">
                                <label>发货时间：</label>
                                <input type="text" class="form-control date-picker J_shipments_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_shipments_end" value="">
                            </div>
                        </div>
                        <div class="col-xs-12 text-right">
                            <div class="btn-row">
                                <a href="#" class="btn btn-danger J_search_btn">搜索</a>
                                <a href="#" class="btn btn-default" data-toggle="modal" data-target="#exportOrder" data-status="3">导出订单</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="J_order_received"></div>
            <!--pagination-->
            <div class="pull-right" id="J_received_page"></div>
        </div>
        <!-- 已取消 -->
        <div role="tabpanel" class="tab-pane fade" id="order_cancel">
            <!-- 订单筛选 -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong class="high-lighted">订单筛选</strong>
                </div>
                <div class="panel-body J_search_box">
                    <div class="row">
                        <div class="col-xs-4 form-horizontal">
                            <div class="form-group">
                                <label for="order_num4" class="col-xs-4">订单号:</label>
                                <div class="col-xs-8">
                                    <input id="order_num4" type="text" class="form-control J_no_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_client4" class="col-xs-4">收货人:</label>
                                <div class="col-xs-8">
                                    <input id="order_client4" type="text" class="form-control J_name_input">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 form-horizontal">
                            <div class="form-group">
                                <label for="buy_acc4" class="col-xs-4">购买帐号:</label>
                                <div class="col-xs-8">
                                    <input id="buy_acc4" type="text" class="form-control J_buy_account">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_address4" class="col-xs-4">收货地址:</label>
                                <div class="col-xs-8">
                                    <input id="order_address4" type="text" class="form-control J_address_input">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 time">
                            <div class="form-group">
                                <label>下单时间：</label>
                                <input type="text" class="form-control date-picker J_create_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_create_end" value="">
                            </div>
                            <div class="form-group">
                                <label>付款时间：</label>
                                <input type="text" class="form-control date-picker J_pay_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_pay_end" value="">
                            </div>
                        </div>
                        <div class="col-xs-12 text-right">
                            <div class="btn-row">
                                <a href="#" class="btn btn-danger J_search_btn">搜索</a>
                                <a href="#" class="btn btn-default" data-toggle="modal" data-target="#exportOrder" data-status="4">导出订单</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="J_order_cancel"></div>
            <!--pagination-->
            <div class="pull-right" id="J_cancel_page"></div>
        </div>
        <!-- 已关闭 -->
        <div role="tabpanel" class="tab-pane fade" id="order_close">
            <!-- 订单筛选 -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong class="high-lighted">订单筛选</strong>
                </div>
                <div class="panel-body J_search_box">
                    <div class="row">
                        <div class="col-xs-4 form-horizontal">
                            <div class="form-group">
                                <label for="order_num5" class="col-xs-4">订单号:</label>
                                <div class="col-xs-8">
                                    <input id="order_num5" type="text" class="form-control J_no_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_client5" class="col-xs-4">收货人:</label>
                                <div class="col-xs-8">
                                    <input id="order_client5" type="text" class="form-control J_name_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_trans5" class="col-xs-4">物流公司:</label>
                                <div class="col-xs-8 choose-express">
                                  <input type="text" />
                                  <select class="form-control"></select>
                                  <div style="left: 15px; width: 186.22px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 form-horizontal">
                            <div class="form-group">
                                <label for="buy_acc5" class="col-xs-4">购买帐号:</label>
                                <div class="col-xs-8">
                                    <input id="buy_acc5" type="text" class="form-control J_buy_account">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_address5" class="col-xs-4">收货地址:</label>
                                <div class="col-xs-8">
                                    <input id="order_address5" type="text" class="form-control J_address_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="trans_code5" class="col-xs-4">物流单号:</label>
                                <div class="col-xs-8">
                                    <input id="trans_code5" type="text" class="form-control J_express_no">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 time">
                            <div class="form-group">
                                <label>下单时间：</label>
                                <input type="text" class="form-control date-picker J_create_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_create_end" value="">
                            </div>
                            <div class="form-group">
                                <label>付款时间：</label>
                                <input type="text" class="form-control date-picker J_pay_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_pay_end" value="">
                            </div>
                            <div class="form-group">
                                <label>发货时间：</label>
                                <input type="text" class="form-control date-picker J_shipments_start" value="">
                                <span>-</span>
                                <input type="text" class="form-control date-picker J_shipments_end" value="">
                            </div>
                        </div>
                        <div class="col-xs-12 text-right">
                            <div class="pull-left">
                                <div class="form-group">
                                    <label>关闭时间：</label>
                                    <input type="text" class="form-control date-picker J_close_start" value="">
                                    <span>-</span>
                                    <input type="text" class="form-control date-picker J_close_end" value="">
                                </div>
                            </div>
                            <div class="btn-row">
                                <a href="#" class="btn btn-danger J_search_btn">搜索</a>
                                <a href="#" class="btn btn-default" data-toggle="modal" data-target="#exportOrder" data-status="5">导出订单</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="J_order_close"></div>
            <!--pagination-->
            <div class="pull-right" id="J_close_page"></div>
        </div>
    </div>
</div>


<script type="text/template" id="J_tpl_order">
    {@each orders as it}
        <div class="panel panel-danger">
              <div class="panel-heading">
                    <span>订单编号：${it.order_no}</span>
                    <span>下单时间：${it.create_time}</span>
                    <span>付款时间：${it.pay_time}</span>
                    <span style="margin-right: 0">付款方式：${it.pay_method}</span>
                    <a href="/order/detail?no=${it.order_no}" target="_blank" class="pull-right">订单详情 <i class="glyphicon glyphicon-chevron-right"></i></a>
              </div>
              <div class="panel-body">
                {@each it.items as item}
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="media text-left">
                                <div class="media-left media-middle">
                                    <img src="${item.image}">
                                </div>
                                <div class="media-body media-middle">
                                    <h5 class="media-heading">${item.title}</h5>
                                    <ul class="list-unstyled text-muted">
                                        {@each item.attributes as attr}
                                            <li>${attr.attribute}：${attr.option}</li>
                                        {@/each}
                                    </ul>
                                    <div>￥<strong>${item.price|tofixed_build}</strong><span class="pull-right">×${item.count}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <strong>备注：</strong>
                            <p class="text-muted">${item.comments}</p>
                        </div>
                    </div>
                {@/each}
                <ul class="list-unstyled">
                    <li><strong>收货人：</strong>${it.consignee}</li>
                    <li><strong>联系电话：</strong>${it.mobile}</li>
                    <li><strong>收货地址：</strong>${it.address}</li>
                    <li><strong>优惠券：</strong>${it|juige_coupon}</li>
                    {@if it.status >= 3}
                    <li><strong>已退款：</strong>${it.refund_rmb|tofixed_build}元</li>
                    {@/if}
                </ul>
                <div class="btn-row text-right">
                    <p>
                        <strong>订单金额:</strong>
                        <span class="high-lighted">￥<span class="h4">${it.total_fee|tofixed_build}</span></span>
                    </p>
                    {@if it.status === 1}
                        <a href="#modalDealOrder" data-toggle="modal" class="btn btn-danger" data-no="${it.order_no}" data-total="${it.total_fee}" data-pay="${it.pay_time}">发货</a>
                    {@/if}
                        <a href="/print?order_id=${it.order_no}" target="_blank" class="btn btn-default">打印发货单</a>
                </div>
              </div>
        </div>
    {@/each}
</script>

<!-- modal -->
<div class="apx-modal-deal-order modal fade" id="modalDealOrder" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close J_close_btn" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <div class="modal-title" id="myModalLabel">
                    <strong><span>订单号 : </span><span class="J_order_no"></span></strong>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-xs-4 text-right control-label">订单金额：</label>
                        <div class="col-xs-7">
                            <p class="form-control-static text-danger h3"><strong class="J_total_fee"></strong></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 text-right control-label">付款日期：</label>
                        <div class="col-xs-7">
                            <p class="form-control-static J_pay_time"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="logisticName" class="col-xs-4 text-right control-label">物流公司：</label>
                        <div class="col-xs-7 choose-express">
                          <input type="text" />
                          <div style="left: 15px; width: 237.16px;"></div>
                          <select class="form-control"><option>选择物流</option></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="logisticSeries" class="col-xs-4 text-right control-label">物流单号：</label>
                        <div class="col-xs-7 cash-after">
                            <input type="text" id="logisticSeries" placeholder="请输入物流单号后确认发货" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-offset-4 col-xs-7">
                            <p class="form-control-static text-danger">*请确认物流信息准确无误</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="J_shipments">确认发货</button>
            </div>
        </div>
    </div>
</div>

<!-- 导出 -->
<div class="apx-modal-deal-order modal fade" id="exportOrder" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close J_close_btn" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <div class="modal-title">
                    <strong>选择导出类型</strong>
                </div>
            </div>
            <div class="modal-body">
                <div class="row form-group export-alert">
                    <label class="col-xs-4">
                        导出全部
                        <input type="radio" name="export" value="1" checked="true">
                    </label>
                    <label class="col-xs-4">
                        导出订单
                        <input type="radio" name="export" value="0">
                    </label>
                    <label class="col-xs-4">
                        导出商品
                        <input type="radio" name="export" value="2">
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="J_export_btn">确认导出</button>
            </div>
        </div>
    </div>
</div>
