<?php
$this->params = ['js' => 'js/coupon-active.js','css'=>'css/coupon-active.css'];
$this->title = '九大爷平台 - 激活优惠券';
?>
<nav class="bottom-nav bottom-nav-coupon">
    <a href="index">
        <i></i>
    </a>
    <span class="spam-first">优惠券列表</span>
    <span></span>
</nav>
<main class="containers">
    <div class="coupon-activation">
        <div class="coupon-height"></div>
        <div class="coupon-input">
            <div class="coupon-no-pass">序列号：</div>
            <div class="coupon-input-no-pass">
                <input type="text" id="coupon-no" maxlength="15" placeholder="请输入15位序列号"/>
            </div>
            <div class="coupon-no-pass">密码：</div>
            <div class="coupon-input-no-pass">
                <input type="password" id="password" maxlength="8"  placeholder="请输入8位优惠券密码"/><img src="/images/eye.png">
            </div>
            <button>立即激活</button>
        </div>
    </div>
</main>