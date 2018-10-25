<?php
$this->params = [
    'js' => 'js/recharge.js',
    'css' => 'css/recharge.css',
];
?>
<div class="acc-recharge">
    <div class="title">账户充值</div>
    <div class="form-box">
        <div class="form-group">
            <label for="" class="control-label text-right">余额：</label>
            <input type="number" id="recharge_amount" class="form-control" name="" value="">
            <span>&nbsp;&nbsp;元</span>
        </div>
        <div class="form-group">
            <label for="" class="control-label text-right">选择支付方式：</label>
            <div class="pay-ment">
                <div class="select pay-box" data-payment="2">
                    <img src="/images/new-account/Alipay.png" alt="">
                    <span>支付宝</span>
                </div>
                <div class="pay-box hidden">
                    <img src="/images/new-account/wepay.png" alt="">
                    <span>微信</span>
                </div>
            </div>
        </div>
        <span class="btn btn-danger J_go_pay">确定</span>
    </div>
    <div class="footer-tip">
            <hr>
            <p class="h5 text-center" style="color: #999;">温馨提示：充值成功后，余额可能存在延迟现象，如有问题，请咨询客服</p>
        </div>
</div>
