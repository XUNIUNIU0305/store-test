<?php
$this->params = ['js' => ['js/qrcode.js','js/gpubs-pick-list.js'], 'css' => 'css/gpubs-pick-list.css'];
$this->title = '自提拼购提货';
?>

<style>
    body {
        background-color: #f2f2f2;
    }
</style>

<div class="self-delivery-box" id="self-delivery-box">
    <div class="self-delivery-head">
        <div class="search">
            <div class="search-box">
                <img class="search-pic" src="/images/self_delivery/search_26_ixon.png" alt="">
                <input class="search-prompt" id="search-prompt" type="text" placeholder="商品名称"/>
            </div>
            <a href="#" class="search-txt" id="search-btn">搜索</a>
        </div>
        <ul class="head-list" id="head-list">
            <li class="head-item active" data-index="0" data-status="-1">全部</li>
            <li class="head-item" data-index="1" data-status="2">未提货</li>
            <li class="head-item" data-index="2" data-status="3">部分提货</li>
            <li class="head-item" data-index="3" data-status="4">提货完成</li>
        </ul>
    </div>

    <div class="section" id="section">
        <ul class="self-delivery-section">
            
        </ul>

        <ul class="self-delivery-section hidden">
            
        </ul>

        <ul class="self-delivery-section hidden">
            
        </ul>

        <ul class="self-delivery-section hidden">
            
        </ul>
    </div>

    <div class="no-more hidden" id="no-more">
        <span class="more-line"></span>
        <span>没有更多拼购了哟~</span>
        <span class="more-line"></span>
    </div>
    
    <div class="unrelated hidden" id="unrelated">
        <img class="unrelated-pic" src="/images/self_delivery/spelling_no.png" alt="">
        <div>啊哦~你暂无相关拼购哦!</div>
        <a class="unrelated-btn" href="/">前往首页逛逛</a>
    </div>

</div>

<div class="cargo-floating-layer hidden" id="cargo-floating-layer">
    <div class="layer-box">
        <span class="layer-tit">提货二维码</span>
        <div class="qr-code" id="qrcode"></div>
        <div class="layer-close"><img id="layer-close" src="/images/purchase_order_details/close.png" alt=""></div>
    </div>
</div>

<div id="topAnchor">
    <!-- 快速导航列表 -->
    <div class="fast-guid-list hidden" id="fast-guid-list"></div>
    <div class="guid-list" id="guid-list">
        <div class="pack-up" id="pack-up"></div>
        <ul class="list">
            <li class="pingou-index"></li>
            <!-- <li class="search"></li> -->
            <li class="my-pingou"></li>
            <li class="ziti-pingou-tihuo"></li>
        </ul>
    </div>
    <!-- 返回顶部 -->
    <a href="#top"><div href="#self-delivery-box" class="G_group-share-back-top"></div></a>
</div>

<script type="text/template" id="section-item">
    {@if total > 0 }
        {@each data as it}
            <li class="section-item">
                <div class="item-tit">
                    <img class="tit-pic" src="/images/self_delivery/store_32_icon.png" alt="">
                    <span>${it.product.brand_name}</span>
                    {@if it.status == 2}
                        <span class="section-status">未提货</span>
                    {@else if it.status == 3}
                        <span class="section-status">部分提货</span>
                    {@else if it.status == 4}
                        <span class="section-status">提货完成</span>
                    {@/if}
                </div>
                <div class="item-section">
                    <a class="section-pic" href="/goods/detail?id=${it.product.product_id}"><img src="${it.product.image}" alt=""></a>
                    <a href="/goods/detail?id=${it.product.product_id}">
                        <div class="item-cont">
                            {@if it.group.gpubs_type == 1}
                                <i class="sec-icon zt-icon"></i>
                            {@else if it.group.gpubs_type == 2}
                                <i class="sec-icon sh-icon"></i>
                            {@/if}
                            <span class="item-txt-1">${it.product.title}</span>
                            <span class="item-txt-2">
                                {@each it.product.sku as item,index}
                                    ${item.selectedOption.name}{@if index < it.product.sku.length-1}; {@/if}
                                {@/each}
                            </span>
                            <div class="item-txt-3">
                                <span>
                                    {@if it.group.gpubs_rule_type == 1}
                                        ${it.group.min_member_per_group}人    <!-- 人数 -->
                                    {@else if it.group.gpubs_rule_type == 2}
                                        ${it.group.min_quantity_per_group}件    <!-- 数量 -->
                                    {@else if it.group.gpubs_rule_type == 3}
                                        ${it.group.min_member_per_group}人
                                        ${it.group.min_quanlity_per_member_of_group}件  <!-- 人数 + 数量 -->
                                    {@/if}
                                </span>                              
                                拼团价：<span class="item-money">￥${it.product.product_sku_price}</span>
                                <span class="number">x${it.product.quantity}</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item-total">
                    <span>实付金额：<span class="amount-payment">￥${it.product.total_fee}</span></span>
                </div>
                <div class="item-footer">
                    <div class="J-footer">
                        {@if it.status == 2 || it.status == 3}
                            <a class="J-pic" href="#"><img src="/images/self_delivery/code_icon.png" alt=""></a>
                            <span class="J-txt">提货码</span>
                            <span class="J-number" data-flag='true' data-code='${it.picking_up_number}'>${it.picking_up_number}</span>
                        {@/if}
                    </div>
                    <a href="/member/gpubs-pick/detail?id=${it.id}">订单详情</a>
                </div>
            </li>
        {@/each}
    {@/if}
</script>
