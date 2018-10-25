<?php
$this->params = ['js' => 'js/account-express.js', 'css' => 'css/account-express.css'];
$this->title = '';
?>
<main class="container wechat-express">
    <nav class="bottom-nav-express">
        <span>物流信息</span>
    </nav>
    <ul class="express-detail">
        <div class="order-info">
            <div class="img-box">
                <img class="J_order_img" alt="">
            </div>
            <div class="text-box">
                <p class="title-1">
                    订单状态：<span class="J_order_status"></span>
                </p>
                <p class="title-2">
                    订单编号：<span class="J_order_no"></span>
                </p>
                <p class="title-2 J_create_time"></p>
            </div>
        </div>
        <ul class="express-list" id="J_express_list">
            
        </ul>
    </div>
</main>
