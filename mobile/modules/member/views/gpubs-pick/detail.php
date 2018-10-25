<?php
$this->params = ['js' => ['js/qrcode.js','js/clipboard.min.js','js/gpubs-pick-detail.js'], 'css' => 'css/gpubs-pick-detail.css'];
$this->title = '订单详情';
?>

<style>
    body {
        background-color: #f2f2f2;
    }
</style>

<div class="purchase-order-details-box" id="collage-detail-box">
    
</div>

<div class="cargo-floating-layer hidden" id="cargo-floating-layer">
    <div class="layer-box">
        <span class="layer-tit">提货二维码</span>
        <div class="qr-code" id="qrcode"></div>
        <div class="layer-close"><img id="layer-close" src="/images/purchase_order_details/close.png" alt=""></div>
    </div>
</div>

<div class="purchase-order-details-footer">
    <a class="footer-btn f-btn" href="/member/gpubs-pick/index">返回提货列表</a>
    <a class="footer-btn" href="javascript:void(0)" onclick="qimoChatClick();">联系客服</a>
</div>

<script type="text/template" id="collage-detail-cont">
    <div class="purchase-order-details-top">
        {@if data.status == 2}
            <div class="waiting-for-delivery">
                <span>未提货</span>
            </div>
        {@else if data.status == 3}
            <div class="already-shipped already-alone">
                <span>部分提货</span>
                <span class="top-alone">已提货:<span>${data.picked_up_quantity}</span>件</span>
            </div>        
        {@else if data.status == 4}
            <div class="transaction-comp already-alone">
                <span>提货完成</span>
                <span class="top-alone">已提货:<span>${data.picked_up_quantity}</span>件</span>
            </div>
        {@/if}
    </div>
    <div class="lading-code-box">
        <div class="lading-code {@if data.status == 4}hidden{@/if}">
            <span>提货码</span>
            <span class="lading-code-number" data-flag="true" data-code="${data.picking_up_number}">${data.picking_up_number}</span>
            <img class="lading-code-pic" id="lading-code-pic" src="/images/self_delivery/code_icon.png" alt="">
        </div>
    </div>
    <div class="purchase-order-details-head">
        <div class="head-cont">
            <span class="head-tit">自提点信息</span>
            <div class="head-user">
                <span class="user-name">${data.picking_up_address.consignee}</span>
                <span class="user-number">${data.picking_up_address.mobile}</span>
            </div>
            <span class="head-addr">${data.picking_up_address.full_address}</span>
            <span class="self-lifting">${data.spot_name}</span>
        </div>
        <img class="head-pic" src="/images/purchase_order_details/caitiao.png" alt="">
    </div>
    <div class="purchase-order-details-section">
        <div class="sec-box">
            <div class="sec-tit">
                <img class="sec-icon" src="/images/purchase_order_details/store_32_icon.png" alt="">
                <span>${data.product.brand_name}</span>
                <span class="sec-right">自提拼购</span>
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
                <!-- <span class="tips-icon-2"></span> -->
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
    <div class="purchase-order-details-cost">
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
    <div class="purchase-order-details-detail">
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
                <span class="detail-txt">${data.join_datetime}</span>
            </li>
            <li class="detail-item">
                <span class="detail-tit">订单创建：</span>
                <span class="detail-txt">${data.group_establish_datetime}</span>
            </li>
            <li class="detail-item" id="delivery-record">
                {@if data.picking_up_log.length > 0}
                    <span class="detail-tit d-txt" >提货记录：</span>
                    <ul class="d-list">
                        {@each data.picking_up_log as it,index}
                            {@if it.unpicked_quantity == it.quantity_to_pick}
                                <li>
                                    <span>${it.picking_up_datetime}</span>
                                    <span>提货完成</span>
                                </li>
                            {@else}
                                <li>
                                    <span>${it.picking_up_datetime}</span>
                                    <span>第${+index+1}次提货</span>
                                </li>
                            {@/if}
                        {@/each}
                    </ul>
                {@else}
                    <span class="detail-tit d-txt">提货记录：</span>
                    <span class="detail-txt zw-txt">暂无</span>
                {@/if}
            </li>
        </ul>
    </div>
</script>
