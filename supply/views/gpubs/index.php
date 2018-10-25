<?php
use yii\helpers\Html;

$this->params = ['js' => 'js/gpubs.js', 'css' => 'css/gpubs.css'];
?>
<div id="dl"></div>
<!-- 退换货tabs -->
<div class="supply-orders-container">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#order_untreated" data-toggle="tab" data-status="2">已成团<span data-title="已成团"></span></a>
        </li>
        <li>
            <a href="#order_treated" data-toggle="tab" data-status="4">已发货<span data-title="已发货"></span></a>
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
                                <label for="order_num1" class="col-xs-4">团编号:</label>
                                <div class="col-xs-8">
                                    <input id="order_num1" type="text" class="form-control J_no_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_client1" class="col-xs-4">团长名称:</label>
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
                                <label for="order_address1" class="col-xs-4">自提点地址:</label>
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
                                <label for="order_num1" class="col-xs-4">团编号:</label>
                                <div class="col-xs-8">
                                    <input id="order_num1" type="text" class="form-control J_no_input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order_client1" class="col-xs-4">团长名称:</label>
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
                                <label for="order_address1" class="col-xs-4">自提点地址:</label>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="J_order_treated"></div>
            <!--pagination-->
            <div class="pull-right" id="J_treated_page"></div>
        </div>
    </div>
</div>


<script type="text/template" id="J_tpl_order">
    {@each orders as it}
        <div class="panel panel-danger">
              <div class="panel-heading">
                    <span>团编号：${it.order_no}</span>
                    <span>下单时间：${it.create_time}</span>
                    {@if it.status == 4}
                        <span>发货时间：${it.deliver_time}</span>
                    {@/if}
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
                                            <li>${attr.name}：${attr.selectedOption.name}</li>
                                        {@/each}
                                    </ul>
                                    <div>￥<strong>${item.price|tofixed_build}</strong><span class="pull-right">×${item.quantity}</span></div>
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
                    <li><strong>团长名称：</strong>${it.consignee}</li>
                    <li><strong>联系电话：</strong>${it.mobile}</li>
                    <li><strong>自提点地址：</strong>${it.address}</li>
                </ul>
                <div class="btn-row text-right">
                    <p>
                        <strong>订单金额:</strong>
                        <span class="high-lighted">￥<span class="h4">${it.total_fee|tofixed_build}</span></span>
                    </p>
                    {@if it.status === 2}
                        <a href="#modalDealOrder" data-toggle="modal" class="btn btn-danger" data-no="${it.order_no}" data-total="${it.total_fee}" data-pay="${it.pay_time}">发货</a>
                    {@/if}
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
