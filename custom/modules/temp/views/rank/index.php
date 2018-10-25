<?php
$this->params = [
    'css' => 'css/rank.css',
    'js' => ['js/iScroll.js','js/rank.js'],
];
?>
<div class="custom-ranking-container">
    <img src="/images/ranking/jinbi.png" alt="">
    <img src="/images/ranking/fang.png" alt="">
    <img src="/images/ranking/da.png" alt="">
    <div class="end-time">刷新时间：<span id="J_refresh_time"></span></div>
    <div class="ranking-list">
        <table>
            <thead>
                <tr>
                    <th width="120">排名</th>
                    <th>账号</th>
                    <th>手机号</th>
                    <th>昵称</th>
                    <th>账户名称</th>
                    <th>采购金额</th>
                </tr>
            </thead>
            <tbody id="J_list_box">
                
            </tbody>
        </table>
    </div>
</div>
<script type="text/template" id="J_tpl_list">
    <tr class="J_my_rank"></tr>
    {@each _ as it}
        <tr>
            {@if it.rank < 4}
                <td><img src="/images/ranking/${it.rank}.png" alt=""></td>
            {@else}
                <td>${it.rank}</td>
            {@/if}
            <td>${it.account}</td>
            <td>${it.mobile}</td>
            <td>${it.nickname}</td>
            <td>${it.shopname}</td>
            <td>￥${it.consumption|ju_price}</td>
        </tr>
    {@/each}
</script>