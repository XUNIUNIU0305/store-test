<?php
$this->params = ['js' => 'js/gpubs-order-list.js', 'css' => 'css/gpubs-order-list.css'];
$this->title = '我的拼购';
?>

<style>
    body {
        background-color: #F2F2F2;
    }
</style>

<div class="complete-collage-box">
    <div class="complete-collage-head">
        <div class="search">
            <div class="search-box">
                <img class="search-pic" src="/images/complete_collage/search_26_ixon.png" alt="">
                <input class="search-prompt" id="search-prompt" type="text" placeholder="商品名称"/>
            </div>
            <a href="#" class="search-txt" id="search-btn">搜索</a>
        </div>
        <ul class="head-list" id="head-list">
            <li class="head-item active" data-index="0" data-status="-1">全部</li>
            <li class="head-item" data-index="1" data-status="1">拼购中</li>
            <li class="head-item" data-index="2" data-status="2">拼购成功</li>
            <li class="head-item" data-index="3" data-status="0">拼购失败</li>
            <li class="head-item" data-index="4" data-status="0">参团失败</li>
        </ul>
    </div>

    <div class="section" id="section">
        <ul class="complete-collage-section">
            
        </ul>

        <ul class="complete-collage-section hidden">
            
        </ul>

        <ul class="complete-collage-section hidden">
            
        </ul>

        <ul class="complete-collage-section hidden">
            
        </ul>

        <ul class="complete-collage-section hidden">
            
        </ul>
    </div>

    <div class="no-more hidden" id="no-more">
        <span class="more-line"></span>
        <span>没有更多拼购了哟~</span>
        <span class="more-line"></span>
    </div>

    <div class="unrelated hidden" id="unrelated">
        <img class="unrelated-pic" src="/images/complete_collage/spelling_no.png" alt="">
        <div>啊哦~你暂无相关拼购哦!</div>
        <a class="unrelated-btn" href="/">前往首页逛逛</a>
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
    <a href="#self-delivery-box"><div href="#self-delivery-box" class="G_group-share-back-top"></div></a>
</div>

<script type="text/template" id="section-item">
    {@if total > 0 }
        {@each data as it}
            <li class="section-item">
                <div class="item-tit">
                    <img class="tit-pic" src="/images/complete_collage/store_32_icon.png" alt="">
                    <span>${it.product.brand_name}</span>
                    {@if it.is_join != null && it.is_join == 0}
                        <span class="section-status" style="color:#ccc">参团失败</span>
                    {@else}
                        {@if it.group.status == 1}
                            <span class="section-status">拼购中</span>
                        {@else if it.group.status == 2 || it.group.status == 4}
                            <span class="section-status">拼购成功</span>
                        {@else if it.group.status == 0 || it.group.status == 3}
                            <span class="section-status" style="color:#ccc">拼购失败</span>
                        {@/if}
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
                                {@if it.is_join != null && it.is_join == 0}
                                    {@each it.product.sku_attributes as item,index}
                                        ${item.selectedOption.name}{@if index < it.product.sku_attributes.length-1}; {@/if}
                                    {@/each}
                                {@else}
                                    {@each it.product.sku as item,index}
                                        ${item.selectedOption.name}{@if index < it.product.sku.length-1}; {@/if}
                                    {@/each}
                                {@/if}
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
                                拼团价：
                                {@if it.is_join != null && it.is_join == 0}
                                    <span class="item-money">￥${it.product.price}</span>
                                {@else}
                                    <span class="item-money">￥${it.product.product_sku_price}</span>
                                {@/if}
                                <span class="number">x${it.product.quantity}</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item-total">
                    <span>实付金额：<span class="amount-payment">￥${it.product.total_fee}</span></span>
                </div>
                <div class="item-footer">
                    <div>
                        {@if it.is_join != null && it.is_join == 0}
                            <a href="/gpubs/share/inviting-friends?id=${it.group.id}&p_id=${it.product.product_id}&ticket_id=${it.id}">拼购详情</a>
                        {@else}
                            <a href="/gpubs/share/inviting-friends?id=${it.group.id}&p_id=${it.product.product_id}">拼购详情</a>
                            {@if it.group.status == 2 || it.group.status == 4}
                                {@if it.group.gpubs_type == 1}
                                    <!-- <a href="/member/gpubs-pick/detail?id=${it.id}">订单详情</a> -->
                                    <a href="/member/gpubs-pick/index">订单详情</a>
                                {@else if it.group.gpubs_type == 2}
                                    <!-- <a href="/member/gpubs-order/detail?id=${it.id}">订单详情</a> -->
                                    <a href="/member/order/index?status=">订单详情</a>
                                {@/if}
                            {@else if it.group.status == 1}
                                <a href="/gpubs/share/inviting-friends?id=${it.group.id}&p_id=${it.product.product_id}" class="J-footer">邀请好友</a>
                            {@/if}
                        {@/if}
                    </div>
                </div>
            </li>
        {@/each}
    {@/if}
</script>