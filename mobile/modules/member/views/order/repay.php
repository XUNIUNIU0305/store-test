<?php
$this->params = ['js' => 'js/repay.js','css'=>'css/repay.css'];
$this->title = '九大爷平台 - 重新支付';
?>

<div class="container">
    <div class="confirm-payment-wrap">
        <div class="confirm-payment">
            <!-- 需支付 -->
            <div class="payment-money">
                需支付：<span class="Price"><span class="pay-price"></span><span class="pay-decimals"></span>元</span>
            </div>
            <!-- 支付方式 -->
            <div class="mode-payment-wrap">
                <div class="mode-payment" id="mask_select_payment" data-mask="#mask_select_payment">
                    <div class="mode-payment-title">选择支付方式</div>
                    <ul>
                        <li class="mode-payment-way" data-pay="1" data-name="余额支付">
                            <div class="mode-payment-left">
                                <div class="logo">
                                    <img src="/images/order_confirm_detail_page/yue_zhifu.png" alt="">

                                </div>
                                <div class="mode-payment-center">
                                    <p>余额支付</p>
                                    <p class="mode-payment-else mode-payment-else-yue">账户余额
                                        <span></span>
                                    </p>
                                </div>
                            </div>

                            <div class="mode-payment-right">
                                <span></span>
                            </div>
                        </li>
                        <li class="mode-payment-way actived" data-pay="3" data-name="微信支付">
                            <div class="mode-payment-left">
                                <div class="logo">
                                    <img src="/images/order_confirm_detail_page/weixinzhifu_logo.png" alt="">
                                </div>

                                <div class="mode-payment-center">
                                    <p>微信支付</p>
                                    <p class="mode-payment-else safety">微信安全支付</p>
                                </div>
                            </div>

                            <div class="mode-payment-right">
                                <span class="mode-payment-right-btn"></span>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
            <!-- 底部确认栏 -->
            <div class="footer">
                <span class="pattern-payment">余额支付 </span><span class="pattern-money"></span>
            </div>
        </div>
    </div>
</div>