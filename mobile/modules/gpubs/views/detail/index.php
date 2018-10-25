<?php
$this->params = ['js' => 'js/detail.js', 'css' => 'css/detail.css'];
$this->title = '拼购详情';
?>
<style>
    main.container {
        background-color: #f4f5f7;
    }
</style>
<!--top nav-->
<nav class="top-nav">
    <div class="title">
        拼购详情
    </div>
</nav>
<!--main container-->
<main class="container">
    <!--confirm order-->
    <div class="group-activity-detail-container">
        <div class="pro-info">
            <div class="info-header hidden">
                <div class="user-name"></div>
                <div class="pro-num">
                    <p class="num">
                        拼团编号：<span id="J_group_num"></span>
                    </p>
                    <p class="start-num">
                        <span id="J_start_num"></span> 件起拼
                    </p>
                </div>
            </div>
            <div class="pro-content">
                <a href="" class="J_pro_href">
                    <figure class="group-order-item">
                        <aside>
                            <img id="J_pro_main_img">
                        </aside>
                        <main>
                            <div class="description J_pro_title"></div>
                            <div class="group-price">
                                拼购价：<span id="J_group_price_box"></span>
                            </div>
                            <div class="param">单独购买价：<span id="J_pro_price_box"></span></div>
                        </main>
                    </figure>
                </a>
            </div>
        </div>
        <div class="activity-info hidden">
            <div id="retroclockbox" style="text-align: center; margin: 6px 0;font-size: 14px;">
                剩余时间：
                <span>00</span>:
                <span>00</span>:
                <span>00</span>
            </div>
            <div class="balance-pro">
                还差<span id="J_balance_num"></span>件拼购成功
            </div>
            <ul class="user-list">
                
            </ul>
            <div class="handle-box J_start_buy_btn">
                立即参团
            </div>
            <div class="address-box">
                <p>
                    自提点：<span id="J_address"></span>
                </p>
                <p>
                    联系人：<span id="J_consignee"></span>
                </p>
            </div>
        </div>
        <div class="start-group-container hidden">
            <span class="btn J_start_buy_btn">我要开团</span>
        </div>
        <div class="group-rule">
            <div class="rule-title">
                <span>拼购规则</span>
                <span class="hidden">查看详情</span>
            </div>
            <div class="rule-list">
                <span>开团</span>
                <span>邀请好友</span>
                <span>参团成功</span>
            </div>
        </div>
    </div>
</main>

<div id="maskLayersh"  class="maskLayersh maskProDetail J_buy_option" style="display:none;">
    <div class="maskLayersh-shop">
        <div class="maskLayersh-shop-top">
            <figure class="commodity-item">
                <aside>
                    <img class="J_buy_img">
                </aside>
                <main>
                    <div class="description"><span class="J_product_price"></span><em class="J_buy_cancel"></em></div>
                    <div class="param">库存 <span class="J_product_inventory"></span>件</div>
                    <div class="param">已选：<span class="J_buy_attribute"></span></div>
                </main>
            </figure>
        </div>
        <div class="goods_attr">
            <div class="goods_attr_items">
                <script type="text/template" id="J_tpl_buy_attribute">
                    {@each _ as it,index}
                     <div class="maskLayersh-classification J_attr_box" data-id="${it.id}">
                        <span data-id="${it.id}">${it.name}</span>
                        <ul>
                            {@each it.options as option,index2}
                            <li data-id="${option.id}">
                                <input type="radio" name="attr${index}" calss="J_attr_input${index}" value="${index2}" autocomplete="off">
                                ${option.name}</li>
                            {@/each}
                        </ul>
                    </div>
                    {@/each}
                </script>
            </div>
            <div class="maskLayersh-num">
                <p class="mask-num">数量</p>
                <div class="num-add-sub">
                    <div id="subtract"></div>
                    <input class="input-number" type="number" id="number" value="1" />
                    <div id="add"></div>
                </div>
            </div>
        </div>
        <div class="maskLayersh-but">
            <button class="but-cancel-datermine J_buy_cancel">取消</button>
            <button class="but-cancel-datermine J_buy_submit">立即购买</button>
        </div>
    </div>

</div>
<script type="text/javascript" src='http://res.wx.qq.com/open/js/jweixin-1.2.0.js'></script>

<script type="text/template" id="J_tpl_user_list">
    {@each _ as it}
        <li>
            <div class="img-box">
                <img src="/images/logo.png" alt="">
            </div>
            <div class="item-info">
                <p class="name">${it.custom_user_account}</p>
                <p class="time">${it.join_datetime}</p>
            </div>
            <div class="item-num">
                ${it.quantity}件
            </div>
        </li>
    {@/each}
</script>