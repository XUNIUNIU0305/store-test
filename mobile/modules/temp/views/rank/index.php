<?php
$this->params = [
    'css' => 'css/rank.css',
    'js' => 'js/rank.js',
];
?>
<style>
    body {
        background: #6519b8;
    }
</style>
<div class="wechat-ranking-container">
    <div class="end-time">
        刷新时间：<span id="J_refresh_time"></span>
    </div>
    <div class="ranking-list">
        <div class="list-header hidden">
            <div class="left">
                <p>我的排名</p>
                <p id="my_rank"></p>
            </div>
            <div class="middle">
                <p id="my_account"></p>
                <p id="my_shopname"></p>
            </div>
            <div class="right">
                <p id="my_mobile"></p>
                <p class="price" id="my_price"></p>
            </div>
        </div>
        <div class="list-body" id="J_list_box">
           
        </div>
    </div>
</div>
<script type="text/template" id="J_tpl_list">
    <tr class="J_my_rank"></tr>
    {@each _ as it}
        <div class="item">
            <div class="left">
            {@if it.rank < 4}
                <img src="/images/ranking/${it.rank}.png" alt="">
            {@else}
                ${it.rank}
            {@/if}
            </div>
            <div class="middle">
                <p>${it.account}</p>
                <p>${it.shopname}</p>
            </div>
            <div class="right">
                <p>${it.mobile}</p>
                <p class="price">￥${it.consumption|ju_price}</p>
            </div>
        </div>
    {@/each}
</script>