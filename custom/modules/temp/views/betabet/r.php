<?php
$this->params = ['css' => 'css/r2.css', 'js' => 'js/r2.js'];
?>

<div class="summaryreductiondatas-top">
    <div class="firsttitle"><span id="province"></span>区域销售金额汇总</div>
</div>
<div class="summaryreductiondatas">    
    <div class="region-data amount-top"></div>
    <div class="sum-of-sales-amount">
        <div class="amount-wrap">
            <div class="summary-wrap-l">
                <div class="region-info">
                    <div class="region">
                        所在区域：
                        <div class="region-wrap">
                            <div class="region-icon"></div>
                            <select class="region-list" id="region-list">

                            </select>
                        </div>
                        <div class="region-tit">
                            区域销售总额(万元):
                            <div class="region-money" id="region-money"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="summary-wrap-r">
                <div class="commodity-model">商品型号</div>
                <ul class="model-list" id="model-list">
                    
                </ul>
            </div>
        </div>
    </div>
    <div class="region-data amount-bottom"></div>
    <div class="region-data region-data-top"></div>
    <div class="region-data-list">
        <div class="data">
            <table class="data-tab">
                <thead>
                    <tr>
                        <th>编号</th>
                        <th>所在区域</th>
                        <th>金额(万元)</th>
                    </tr>
                </thead>
                <tbody id="tab-tbody-1">
                    
                </tbody>
            </table>
            <table class="data-tab">
                <thead>
                    <tr>
                        <th>编号</th>
                        <th>所在区域</th>
                        <th>金额(万元)</th>
                    </tr>
                </thead>
                <tbody id="tab-tbody-2">

                </tbody>
            </table>
            <table class="data-tab">
                <thead>
                    <tr>
                        <th>编号</th>
                        <th>所在区域</th>
                        <th>金额(万元)</th>
                    </tr>
                </thead>
                <tbody id="tab-tbody-3">
                    
                </tbody>
            </table>
        </div>
        <!-- <div class="look-at-more"><a href="#">加载更多</a></div> -->
        <div class="no-data hidden">
            <img src="/images/2018-10-r/no-data.png" class="no-data-pic">暂无数据
        </div>
    </div>
    <div class="region-data region-data-bottom"></div>
</div>
<div class="summaryreductiondatas-bottom"></div>

<script type="text/template" id="region-list-item">
    <option disabled {@if id == -1}selected{@/if}>请选择</option>
    {@each data as it}
        <option class="region-list-item" value="${it.area_id}" {@if id == it.area_id}selected{@/if}>${it.area_name}</option>
    {@/each}
</script>

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