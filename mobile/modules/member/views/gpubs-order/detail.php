<?php
$this->params = ['js' => ['js/clipboard.min.js','js/gpubs-order-detail.js'], 'css' => 'css/gpubs-order-detail.css'];
$this->title = '订单详情';
?>

<style>
    body {
        background-color: #f2f2f2;
    }
</style>

<div class="collage-detail-box" id="collage-detail-box">
    
</div>

<div class="collage-detail-footer">
    <a class="footer-btn" href="javascript:void(0)" onclick="qimoChatClick();" id="footer-btn-1">联系客服</a>
    <a class="footer-btn" href="#" id="footer-btn-2">查看物流</a>
    <a class="footer-btn btn-active" href="#" id="footer-btn-3">确认收货</a>
</div>

<script type="text/template" id="collage-detail-cont">
    <div class="collage-detail-top">
        {@if data.delivery_status == 1}
            <div class="waiting-for-delivery">
                <span>等待卖家发货...</span>
            </div>
        {@else if data.delivery_status == 2}
            <div class="already-shipped">
                <span>卖家已发货:</span>
                <span class="top-alone">${data.express_detial.detail[0].context}</span>
                <span class="top-alone">${data.express_detial.detail[0].ftime}</span>
                <a class="top-path" href="/member/express?no=${data.express_detial.nu}"></a>
            </div>
        {@else if data.delivery_status == 3}
            <div class="transaction-comp">
                <img class="comp-pic" src="/images/collage_detail/wancheng.png" alt="">
                <span>交易完成</span>
            </div>
        {@/if}
    </div>
    <div class="collage-detail-head">
        <div class="head-cont">
            <span class="head-tit">收货地址</span>
            <div class="head-user">
                <span class="user-name">${data.picking_up_address.consignee}</span>
                <span class="user-number">${data.picking_up_address.mobile}</span>
            </div>
            <span class="head-addr">${data.picking_up_address.full_address}</span>
        </div>
        <img class="head-pic" src="/images/collage_detail/caitiao.png" alt="">
    </div>
    <div class="collage-detail-section">
        <div class="sec-box">
            <div class="sec-tit">
                <img class="sec-icon" src="/images/collage_detail/store_32_icon.png" alt="">
                <span>${data.product.brand_name}</span>
                <span class="sec-right">送货拼购</span>
            </div>
            <div class="sec-cont">
                <a class="sec-pic" href="/goods/detail?id=${data.product.id}"><img src="${data.product.image}" alt=""></a>
                <a href="/goods/detail?id=${data.product.id}">
                    <div class="cont-txt">
                        <span class="c-txt">${data.product.title}</span>
                        <span class="txt-gray">
                            {@each data.product.sku as item,index}
                                ${item.selectedOption.name}{@if index < data.product.sku.length-1}; {@/if}
                            {@/each}
                        </span>
                        <div class="txt-box">
                            <span class="txt-money">￥${data.product.product_sku_price}</span>
                            <span class="txt-number">x${data.product.quantity}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="sec-tips">
                <span class="tips-icon-1"></span>
                <span>${data.description}</span>
                <span class="tips-icon-2"></span>
            </div>
        </div>
        <div class="sec-footer">
            <span class="foot-txt-1">备注</span>
            {@if data.comment}
                <textarea class="foot-txt-2" disabled>${data.comment}</textarea>
            {@else}
                <span class="no-txt">暂无</span>
            {@/if}
        </div>
    </div>
    <div class="collage-detail-cost">
        <ul class="cost-list">
            <li class="cost-item">
                <span class="cost-tit">商品金额</span>
                <span class="cost-txt">￥${data.product.total_fee}</span>
            </li>
            <li class="cost-item">
                <span class="cost-tit">优惠券金额</span>
                <span class="cost-txt">￥ 0.00</span>
            </li>
            <li class="cost-item">
                <span class="cost-tit">立减</span>
                <span class="cost-txt">￥ 0.00</span>
            </li>
            <li class="cost-item">
                <span class="cost-tit">运费</span>
                <span class="cost-txt">￥ 0.00</span>
            </li>
        </ul>
        <div class="cost-footer">
            <span class="cost-number">数量：<span>${data.product.quantity}</span>件</span>
            <span class="cost-money">实付金额：<span class="money-total">￥${data.product.total_fee}</span></span>
        </div>
    </div>
    <div class="collage-detail-detail">
        <ul class="detail-list">
            <li class="detail-item">
                <span class="detail-tit">订单编号：</span>
                <span class="detail-txt" id="dd-number">${data.detail_number}</span>
                <a class="detail-btn" href="#" data-clipboard-action="copy" data-clipboard-target="#dd-number">复制</a>
            </li>
            <li class="detail-item">
                <span class="detail-tit">支付方式：</span>
                {@if data.pay_method == 1}
                    <span class="detail-txt">余额</span>
                {@else if data.pay_method == 3}
                    <span class="detail-txt">微信</span>
                {@/if}
            </li>
            <li class="detail-item">
                <span class="detail-tit">团编号：&nbsp;&nbsp;&nbsp;</span>
                <span class="detail-txt" id="t-number">${data.group_number}</span>
                <a class="detail-btn" href="#" data-clipboard-action="copy" data-clipboard-target="#t-number">复制</a>
            </li>
            <li class="detail-item">
                <span class="detail-tit">下单时间：</span>
                <span class="detail-txt" id="xd-time">${data.join_datetime}</span>
            </li>
            <li class="detail-item">
                <span class="detail-tit">订单创建：</span>
                <span class="detail-txt" id="cj-time">${data.group_establish_datetime}</span>
            </li>
        </ul>
    </div>
</script>