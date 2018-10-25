<?php
$this->params = ['css' => 'css/p2.css', 'js' => 'js/p2.js'];
?>

<div class="sales_main_page" id="sales_main_page">
    <div class="main_page_top">
        <span class="top_date" id="top_date"></span>
    </div>
    <div class="main_page_head">
        <a class="head-shop head-shop-1" href="/goods/detail?id=2417"></a>
        <a class="head-shop head-shop-2" href="/goods/detail?id=2417"></a>
        <a class="head-shop head-shop-3" href="/goods/detail?id=2417"></a>
        <a class="head-shop head-shop-4" href="/goods/detail?id=2417"></a>
        <a class="head-shop head-shop-5" href="/goods/detail?id=2414"></a>
        <a class="head-shop head-shop-6" href="/goods/detail?id=2414"></a>
    </div>
    <div class="main_page_after_sale">
        <a class="after-sale after-sale-1" href="/temp/betabet/q"></a>
        <a class="after-sale after-sale-2" href="/temp/betabet/x"></a>
    </div>
    <div class="main_page_foot">
        <div class="foot-top"></div>
        <div class="foot-cont">
            <ul class="foot-list" id="foot-list">
                
            </ul>
            <a class="return-top" href="#sales_main_page">返回顶部</a>
        </div>
    </div>
</div>

<script type="text/template" id="foot-list-cont">
    {@each _ as it}
        <li class="foot-list-item">
            <a href="/goods/detail?id=${it.id}">
                <div class="list-item-pic">
                    <img src="${it.main_image}" alt="">
                </div>
                <div class="list-item-cont">
                    <h3 class="item-tit">${it.title}</h3>
                    <p class="characteristic">特色：<span>${it.description}</span></p>
                    <div class="item-foot">
                        <span class="item-foot-money">￥<span>${it.price.min}</span></span>
                        <span class="item-foot-btn">点击购买</span>
                    </div>
                </div>
            </a>
        </li>
    {@/each}
</script>