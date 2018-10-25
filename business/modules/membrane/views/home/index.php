<?php
/**
 * @var $this \yii\web\View
 */
$this->title = '货单列表';
$this->params = ['css' => 'css/index.css', 'js' => 'js/index.js'];
?>
<!-- main area start -->
<div class="apx-business-list-main">
    <div class="order-select">
        <h2 class="title">订单筛选</h2>
        <div class="body">
            <form action="" id="search">
                <ul class="list clearfix">
                    <li>
                        <label>收货人：</label>
                        <input type="text" name="receive_name" class="w-2">
                    </li>
                    <li>
                        <label>购买账号：</label>
                        <input type="text" name="buy_account" class="w-1">
                    </li>
                    <li class="msg-time">
                        <label>下单时间：</label>
                        <input type="text" name="created_start" class="date-picker">
                        <i class="time-line">-</i>
                        <input type="text" name="created_end" class="date-picker">
                    </li>
                    <li>
                        <label>货单状态：</label>
                        <select name="status" id="status-select" class="w-select"></select>
                    </li>
                    <li>
                        <label>货单号：</label>
                        <input type="text" name="order_number" class="w-2">
                    </li>
                    <li>
                        <label>收货地址：</label>
                        <input type="text" name="receive_address" class="w-1">
                    </li>
                    <li class="msg-time">
                        <label>付款时间：</label>
                        <input type="text" name="pay_start"  class="date-picker">
                        <i class="time-line">-</i>
                        <input type="text" name="pay_end"  class="date-picker">
                    </li>

                </ul>
                <button class="c_btn">查询</button>
            </form>
        </div>
    </div>
    <div class="f-line"></div>
    <div class="order-content">
        <div role="tabpanel" class="tab-pane fade in active" id="tab_all">
            <table class="table table-fix top-table text-center">
                <tr>
                    <td class="pro-detail text-left" width="410">商品</td>
                    <td width="120">平台账号</td>
                    <td width="230">用户备注</td>
                    <td width="260">收货人信息</td>
                    <td width="160">状态/操作</td>
                </tr>
            </table>
            <div id="item-box" class="item-box"></div>
            <div id="page"></div>
            <script id="item" type="text/template">
                {@each items as item}
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td colspan="7" class="no-border row bus-order-msg">
                            <div class="text-left col-xs-11">
                                <span>货单号：${item.no}</span>
                                <span class="orser-time">下单时间：${item.createdDate}</span>
                                <span class="orser-time">付款时间：${item.payDate}</span>
                            </div>
                            <div class="text-left col-xs-1">${status[item.status]}</div>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    {@each item.items as i}
                        <tr class="tr-content">
                            <td class="row acc-media-box " id="bus-content">
                                <div class="col-xs-8">
                                    <div class="media text-left">
                                        <a class="media-left media-middle" href="#">
                                            <img src="${i.membrane_product_id === 1 ? '/images/membrane/item.png' : '/images/membrane/apex.jpg'}">
                                        </a>
                                        <div class="media-body media-middle">
                                            <div class="descipe-bus">${i.name}</div>
                                            <div class="price">￥${i.price}</div>
                                            <div class="text-attr">
                                                {@each i.attributes as attribute}
                                                    ${attribute.block}：${attribute.type} &nbsp;
                                                {@/each}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td width="120">${item.account}</td>
                            <td width="230">
                                ${item.remark}
                            </td>
                            <td width="270" class="text-left txt-msg">
                                <div class="person info"><i class="glyphicon glyphicon-user"></i>${item.receiveName}</div>
                                <div class="phone info"><i class="glyphicon glyphicon-earphone"></i>${item.receiveMobile}</div>
                                <div class="address info"><i class="glyphicon glyphicon-home"></i>${item.receiveAddress}</div>
                            </td>
                            <td width="160" class="text-center text-btn">
                                {@if item.status == 2}
                                    <button class="accept-btn btn-k" data-id="${item.no}">接单</button>
                                    <button class="btn-k J_cancel_btn" data-id="${item.no}">取消订单</button>
                                {@else if item.status == 3 }
                                    <button class="finish-btn btn-k" data-id="${item.no}">完成</button>
                                    <button class="btn-k J_cancel_btn" data-id="${item.no}">取消订单</button>
                                {@else if item.status == 4 }
                                    <a class="btn-k" href="/quality/qualityorder/create">填写质保</a>
                                {@/if}
                            </td>
                        </tr>
                    {@/each}
                    </tbody>
                </table>
                {@/each}
            </script>
        </div>
    </div>
</div>

<!-- 禁用弹窗 -->
<div class="apx-modal-business-alert modal fade business-management" id="confirm" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 20px 0;"><i class="glyphicon glyphicon-alert"></i>确定？</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business" id="confirm-success">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>