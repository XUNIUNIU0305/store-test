<?php
$this->title = '九大爷平台 - 支付结果';
?>
<style type="text/css">
	.container-payment {
  background-color: #ececec; }
  .container-payment .payment-all .payment-prompt {
    background-color: #FFFFFF;
    text-align: center;
    padding: 40px 10px;
    margin-bottom: 8px; }
    .container-payment .payment-all .payment-prompt div:first-child {
      height: 26px; }
      .container-payment .payment-all .payment-prompt div:first-child span {
        color: #cf4d44;
        font-size: 17px;
        font-weight: bold;
        vertical-align: middle; }
      .container-payment .payment-all .payment-prompt div:first-child img {
        width: 20px;
        height: 20px;
        background-size: cover;
        vertical-align: middle; }
    .container-payment .payment-all .payment-prompt div:last-child em {
      font-style: normal;
      font-size: 22px;
      font-weight: bold;
      color: #cf4d44; }
  .container-payment .payment-all .payment-order {
    background-color: #FFFFFF;
    padding: 0px 15px;
    font-size: 14px;
    overflow: hidden;
    font-weight: bold; }
    .container-payment .payment-all .payment-order ul {
      margin: 15px 0;
      border-bottom: 1px dashed #999;
      padding-bottom: 15px; }
      .container-payment .payment-all .payment-order ul li span {
        color: #999999; }
      .container-payment .payment-all .payment-order ul li em {
        font-style: normal;
        color: #333333;
        float: right; }
      .container-payment .payment-all .payment-order ul li .em-color {
        color: #cf4d44; }
  .container-payment .payment-all .payment-but {
    background-color: #FFFFFF;
    padding-top: 20px; }
    .container-payment .payment-all .payment-but a {
      background-color: #cf4d44;
      border: 0px;
      width: calc(100% - 30px);
      height: 44px;
      text-align: center;
      line-height: 44px;
      color: #FFFFFF;
      margin: 20px 15px 0;
      font-size: 16px;
      border-radius: 3px; }
</style>
<main class="container-payment">
    <div class="payment-all">
        <div class="payment-prompt">
            <div><img src="/images/successful_payment/no_icon.png"/><span>支付失败</span></div>
        </div>
        <div class="payment-but">
            <a href="/member/index">返回账户中心</a>
            <a href="/index">返回商城首页</a>
        </div>
    </div>
</main>

