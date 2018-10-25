<?php
$this->params = ['js' => 'js/balance.js', 'css' => 'css/payment.css'];
$this->title = '九大爷平台 - 支付结果';
?>

<main class="container-payment">
    <div class="payment-all">
        <div class="payment-prompt">
            <div><img src="/images/successful_payment/successful_payment.png"/><span>支付成功</span></div>
            <div><em>￥<?PHP echo sprintf("%0.2f",$totalFee); ?></em></div>
        </div>
        <div class="payment-but">
            <a class="payment-but-href1" href="javascript:void(0)">返回账户中心</a>
            <a class="payment-but-href2" href="javascript:void(0)">返回商城首页</a>
        </div>
    </div>
</main>