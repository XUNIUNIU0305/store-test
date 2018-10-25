<?php
$this->params = ['css' => 'css/o2.css', 'js' => 'js/o2.js'];
?>
<div class="summaryreductiondata">
    <div class="firsttitle"></div>
    <!-- <p class="activitytime">2018.10.17~2018.10.19</p> -->
    <div class="sales">
        <div class="salesleft">
            <div class="sales-price">
                <p>销售总额<span>（万元）</span></p>
                <div class="sales-price-num"><span></span></div>
            </div>
            <div class="sales-number">
                <p>销售总量<span>（件）</span></p>
                <div class="sales-number-num"></div>
            </div>
        </div>
        <div class="salesright">
            <p>商品型号</p>
            <ul class="productmodel-wrap" id="productmodel-wrap"></ul>
            <!-- <span>全合成</span><span class="productmodeltype">5W-40</span><span class="productmodelsize">4L</span><span>1000件</span> -->
            <script id="productmodels" type="text/template">
                {@each data as list}
                    <li class="productmodel">
                        {@each list.sku_attributes as attributes}
                            ${attributes.attribute}：<span>${attributes.option},</span>
                        {@/each}
                        已售：<span>${list.quantity}件</span>
                    </li> 
                {@/each}
            </script>
        </div>
    </div>
    <div class="realtimedata">
        <div class="real-time-summary">
            <span class="shengjihuizong">省级实时汇总</span>
            <div class="realtimesummary-description"><span class="realtimesummary-bar"></span>销售金额/万元</div>
        </div>
        <!-- echarts图标 -->
        <div id="chartmain"></div>
    </div>
</div>
