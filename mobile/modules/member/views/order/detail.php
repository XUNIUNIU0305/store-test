<?php
$this->params = ['js' => 'js/order-detail.js','css'=>'css/orderDetail.css'];
$this->title = '九大爷平台 - 会员订单详情';
?>

<nav class="bottom-nav bottom-navs">
    <span>订单详情</span>
</nav>
<main class="container containers">
    <div class="wechat-order">
        <div class="order-hidden"></div>
        <div class="panel">
            <ul class="order-shop-info">
                <li>订单编号：<span class="J_order_no"></span><em class="J_order_status"></em></li>
                <li>创建时间：<span class="J_create_time"></span></li>
                <li>支付方式：<em id="J_pay_ment"></em></li>
                <li class="J_status_text hidden"> 亲,本次交易已完成,欢迎再次光临!</li>
            </ul>
        </div>
        <div class="panel">
            <ul class="order-shop-acc-info">
                <li>收货人：<span class="J_consignee"></span><em class="J_mobile"></em></li>
                <li>收货地址：<span class="J_address"></span></li>
            </ul>
        </div>
        <div class="panel">
            <div class="order-panel-heading">
                <p class="J_storename"></p>
                <a href="#"></a>
            </div>
            <div class="panel-body">
                <div class="shop-latest-order">
                    <div class="border-box J_order_detail">

                        <script type="text/template" id="J_tpl_detail">
                            {@each items as it}
                                <a href="/goods/detail?id=${it.product_id}">
                                    <!-- single item-->
                                    <figure class="order-item">
                                        <aside>
                                            <img src="${it.image}">
                                        </aside>
                                        <main>
                                            <div class="description">${it.title}</div>
                                            <div class="param">
                                                {@each it.attributes as x,index}
                                                <span>${x.attribute}-${x.option}{@if index < it.attributes.length-1};{@/if}</span>
                                                {@/each}
                                            </div>
                                            <div class="price"><span>￥${it.price}</span></div>
                                            <span class="ammount">${it.count}</span>
                                        </main>
                                    </figure>
                                </a>
                                <p class="remark">备注：${it.comments}</p>
                            {@/each}
                        </script>

                    </div>
                </div>
            </div>
            <div class="service-click-container">
                <span onClick="qimoChatClick();">联系客服</span>
            </div>
        </div>
        <div class="panel order-acc-shop-info">
            <ul>
                <li>商品总额：<em class="J_total"></em></li>
                <li>优惠券：<em id="J_coupon_price"></em></li>
            </ul>
            <p class="total">实付款：<span class="J_paid"></span></p>
        </div>
    </div>
</main>
<div class="query-express hidden">
    <a href="#" class="btn J_jump_express">物流查询</a>
</div>