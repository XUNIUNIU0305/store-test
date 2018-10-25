<?php
$this->params = ['js' => 'js/djy-user.js', 'css' => 'css/djy-user.css'];
?>

<div class="summaryreductiondatas-top">
    <div class="firsttitle">销售汇总</div>
</div>
<div class="summaryreductiondatas">
    <div class="region-data amount-top"></div>
    <div class="sum-of-sales-amount">
        <div class="amount-wrap">
            <div class="summary-wrap-l">
                <h3 class="region-tit">区域销售总额(万元)：</h3>
                <div class="region-money"><span id="region-money"></span></div>
            </div>
            <div class="summary-wrap-r">
                <h3 class="commodity-model">商品型号</h3>
                <ul class="model-list" id="model-list">
                    
                </ul>
            </div>
        </div>
    </div>
    <div class="region-data amount-bottom"></div>
    <div class="region-data region-data-top"></div>
    <div class="region-data-list">
        <table class="data-tab">
            <thead>
                <tr>
                    <th>编号</th>
                    <th>门店账号</th>
                    <th>门店名称</th>
                    <th>门店老板</th>
                    <th>老板手机</th>
                    <th>金额(元)</th>
                </tr>
            </thead>
            <tbody id="data-tab-tbody">
                
            </tbody>
        </table>
        <div class="look-at-more hidden"><a href="javascript:void(0)" id="look-at-more">加载更多</a></div>
        <div class="no-data hidden">
            <img class="no-data-pic" src="/images/2018-10-r/no-data.png" alt=""/>暂无数据
        </div>
    </div>
    <div class="region-data region-data-bottom"></div>
</div>
<div class="summaryreductiondatas-bottom"></div>

<script type="text/template" id="model-list-item">
    {@each _ as item}
        <li class="model-list-item">
            {@each item.sku_attributes as it}
                ${it.attribute}：<span class="item-1 item-2">${it.option}</span>
            {@/each}
            已售：<span class="item-2">${item.quantity}件</span>
        </li>
    {@/each}
</script>