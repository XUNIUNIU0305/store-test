<?php
$this->params = ['js' => 'js/refund_index.js', 'css' => 'css/refund_index.css'];

$this->title = '九大爷平台 - 账户中心 - 退换货';
?>

<div class="top-title">售后列表</div>
<div class="br"></div>
<div class="apx-acc-title refund-bg">
    <ul class="nav nav-tabs apx-acc-order-nav" role="tablist" id="J_tab_box">
        <li role="presentation" class="active" data-type="0">
            <a href="#tab_unclassified" role="tab" data-toggle="tab">未分类</a>
        </li>
        <li role="presentation" data-type="1">
            <a href="#tab_exchange" role="tab" data-toggle="tab">换货类</a>
        </li>
        <li role="presentation" data-type="2">
            <a href="#tab_refund" role="tab" data-toggle="tab">退货类</a>
        </li>
        <li class="pull-right">
            <div class="input-group">
                <input type="text" class="form-control" id="J_search_input" placeholder="售后单号">
                <span class="input-group-btn" id="J_refund_search">
                    <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-search"></i></button>
                </span>
            </div>
            <select class="selectpicker" id="J_refund_status" data-width="166">
                <option value="">请选择售后单状态</option>
                <option value="0">待客服审核</option>
                <option value="2">已驳回</option>
            </select>
        </li>
    </ul>
</div>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in active refund-list-item" id="tab_unclassified">
        <script type="text/template" id="J_tpl_refund">
            {@each codes as it}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row small">
                            <div class="col-xs-3  style-control">售后单号：<span>${it.refund_order_code}</span></div>
                            <div class="col-xs-3 style-control">生成时间：<span>${it.order_create_time}</span></div>
                            <div class="col-xs-3 style-control">关联订单号：<span>${it.order_code}</span></div>
                            <div class="col-xs-3 text-right">售后单类别：${it.refund_type_txt}</div>
                        </div>
                    </div>
                    <table class="table text-center">
                        <tbody>
                            <tr>
                                <td class="row acc-media-box">
                                    <div class="col-xs-12">
                                        <div class="media text-left">
                                            <a class="media-left media-middle" href="/product?id=${it.goods_id}" target="_blank">
                                                <img src="${it.goods_img}">
                                            </a>
                                            <div class="media-body media-middle">
                                                <a href="/product?id=${it.goods_id}"  target="_blank"><p>${it.goods_title}</p></a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td width="74">${it.refund_status|status_build}</td>
                                <td width="92">¥${it.goods_price}</td>
                                <td width="64">×${it.refund_quantity}</td>
                                <td width="112">
                                    <div>总金额</div>
                                    <div>¥${it.refund_total}</div>
                                </td>
                                <td width="98">
                                    <a target="_blank" href="/account/refund/detail?refund_code=${it.refund_order_code}" class="text-danger">查看详情</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            {@/each}
        </script>
    </div>
    <div role="tabpanel" class="tab-pane fade refund-list-item" id="tab_exchange"></div>
    <div role="tabpanel" class="tab-pane fade refund-list-item" id="tab_refund"></div>
</div>
<!-- pagination -->
<div class="text-right" id="J_refund_page"></div>
