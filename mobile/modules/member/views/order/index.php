<?php
$this->params = ['js' => 'js/order-list.js','css'=>'css/order-list.css'];
$this->title = '九大爷平台 - 订单列表';
?>

<nav class="bottom-nav bottom-nav-my-order">
    <span>我的订单</span>
    <a href="/customization/order" class="btn">定制订单</a>
</nav>
<nav class="bottom-nav bottom-nav-my-orders">
    <div id="status-span"> 
        <span class="J_order_info" status="">所有订单</span>
        <span class="J_order_info" status="0" >待付款</span>
        <span class="J_order_info" status="1">待发货</span>
        <span class="J_order_info" status="2">待收货</span>
        <span class="J_order_info" status="3">已收货</span>
        <span class="J_order_info" status="4">已取消</span>
        <span class="J_order_info" status="5">已关闭</span>
    </div>
</nav>
<main class="containerorder">
    <div class="my_order">
        <div class="panel-body" id="J_order_list">
         <script type="text/template" id="J_tpl_order">
            {@each _.orders as it, index}
            <div class="acc-latest-order">
                <div class="title">
                    <span>${it.storename}</span>
                    <span>
                    {@if it.status == 0}
                        待付款
                    {@else if it.status == 1}
                        待发货
                    {@else if it.status == 2}
                        已发货
                     {@else if it.status == 3}
                        已收货
                     {@else if it.status == 4}
                        已取消
                     {@else if it.status == 5}
                        已关闭
                     {@/if}
                    </span>
                </div>
                <div class="border-box">
                    {@each it.items as x, index}
                    <a href="/member/order/detail?no=${it.order_no}">
                        <!-- single item-->
                        <figure class="order-item">
                            <aside>
                                <img src="${x.image}">
                            </aside>
                            <main>
                                <div class="description">${x.title}</div>
                                <div class="param"><span>${x.attributes|attr_build}</span></div>
                                <div class="price">单价：<span>￥ ${x.price}</span></div>
                                <span class="ammount">${x.count}</span>
                            </main>
                        </figure>
                    </a>
                    {@/each}
                </div>
                <div class="total-info">
                    <span>订单编号：${it.order_no}</span>
                    <span>共${it.items|count_items}件商品 实付：￥${it.total_fee|tofixed_build}</span>
                </div>
                <div class="handle-btn">
                        <a href="" class="btn btn-pay hidden">去付款</a>
                    {@if it.status === 0}
                        <a href="/member/order/repay?no=${it.order_no}&total_fee=${it.total_fee}" class="btn J-payment">立即支付</a>
                    {@/if}
                    {@if it.status === 0 || it.status === 1}
                        <a href="#" class="btn J_cancel_order" data-no="${it.order_no}">取消订单</a>
                    {@/if}
                    {@if it.status === 2}
                        <a href="#" class="btn J_sure_get" data-no="${it.order_no}">确认收货</a>
                    {@/if}
                    {@if it.status > 1 && it.status != 4}
                        <a href="/member/express?no=${it.order_no}" class="btn">查看物流</a>
                    {@/if}
                    <a href="/member/order/detail?no=${it.order_no}" class="btn btn-pay">查看详情</a>
                </div>
            </div>
            {@/each}
             </script>
        </div>
       
    </div>
</main>