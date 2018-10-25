<?php
$this->params = ['js' => 'js/refund_index.js', 'css' => 'css/refund_index.css'];

$this->title = '九大爷平台 - 账户中心 - 退换货';
?>

<!-- 退换货tabs -->
<div class="supply-refund-container">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="J_tab_box">
        <li class="active" data-status="1">
            <a href="#refund_todo" data-toggle="tab">待审核</a>
        </li>
        <li data-status="4">
            <a href="#refund_doing" data-toggle="tab">未处理</a>
        </li>
        <li data-status="-1">
            <a href="#refund_done" data-toggle="tab">已处理</a>
        </li>
        <li data-status="9">
            <a href="#refund_cancel" data-toggle="tab">已取消</a>
        </li>
        <li class="pull-right">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" placeholder="请输入售后单号">
                <span class="input-group-btn" id="J_search_btn">
                    <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-search"></i></button>
                </span>
            </div>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <!--待处理 start-->
        <div role="tabpanel" class="tab-pane fade active in" id="refund_todo">
            <div id="J_todo_refund">
                <script type="text/template" id="J_tpl_refund">
                    <!--table head-->
                    <table class="table table-condensed text-center">
                        <thead>
                            <tr>
                                <td>退换单号</td>
                                <td>商品信息</td>
                                <td>商品单价</td>
                                <td>商品数量</td>
                                <td>商品总额</td>
                                <td>订单状态</td>
                                <td>操作／申请时间</td>
                            </tr>
                        </thead>
                    </table>
                    {@each codes as it}
                        <table class="table table-condensed text-center">
                            <tbody>
                                <tr>
                                    <td><strong>${it.code}</strong></td>
                                    <td>
                                        <div class="media">
                                            <a class="media-left" href="javascript:;">
                                                <img src="${it.image}">
                                            </a>
                                            <div class="media-body media-middle text-left">
                                                <h5 class="media-heading text-ellipsis" title="${it.title}">${it.title}</h5>
                                                <small>${it.order_info.attributes|attr_build}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><strong>¥${it.price|price_build}</strong></td>
                                    <td>${it.quantity}</td>
                                    <td><strong class="text-danger">¥${it.total|price_build}</strong></td>
                                    <td><strong class="text-warning">${it.status|status_build}</strong></td>
                                    <td>
                                        <p><a href="/refund/detail?code=${it.code}" target="_blank"><strong>退换详情</strong></a></p>
                                        <small>${it.create_time}</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    {@/each}
                </script>
            </div>
            <!--panigation-->
            <div class="pull-right" id="J_todo_page"></div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="refund_doing">
            <div id="J_doing_refund"></div>
            <!--panigation-->
            <div class="pull-right" id="J_doing_page"></div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="refund_done">
            <div id="J_done_refund"></div>
            <!--panigation-->
            <div class="pull-right" id="J_done_page"></div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="refund_cancel">
            <div id="J_cancel_refund"></div>
            <!--panigation-->
            <div class="pull-right" id="J_cancel_page"></div>
        </div>
    </div>
</div>

