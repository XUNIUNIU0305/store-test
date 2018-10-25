<?php
$this->params = ['js' => 'js/address-gpubs-add.js','css'=>'css/address-gpubs-add.css'];
$this->title = '九大爷平台 - 新增自提点地址';
?>

<style>
    body {
        background-color: #f2f2f2;
    }
</style>

<div class="newly-added" id="newly-added">
    <div class="newly-added-top">一旦拼购成功，参团成员将至您设置的自提点领取相应商品发起拼购后，自提点将不可更改，请谨慎填写！ </div>
    <ul class="new-addr-list" id="new-addr-list">
        <li class="new-addr-item">
            <span>自提点名称</span>
            <input class="new-addr-prompt" id="spot_name" type="text" />
        </li>
        <li class="new-addr-item">
            <span>收货人</span>
            <input class="new-addr-prompt" id="consignee" type="text" />
        </li>
        <li class="new-addr-item">
            <span>手机号码</span>
            <input class="new-addr-prompt" id="mobile" type="text" maxlength="11" />
            <span class="new-addr-icon hidden" id="new-addr-icon"></span>
        </li>
        <li class="new-addr-item">
            <span>邮政编码</span>
            <input class="new-addr-prompt" id="postal_code" type="text" maxlength="6" />
        </li>
        <li class="new-addr-item">
            <span>所在区域</span>
            <select class="new-addr-sel" id="sel_p">

            </select>
            <select class="new-addr-sel" id="sel_c">
                <option>请选择市</option>
            </select>
            <select class="new-addr-sel" id="sel_a">
                <option>请选择区</option>
            </select>
        </li>
        <li class="new-addr-item">
            <span>详细地址</span>
            <input class="new-addr-prompt" id="detailed_address" type="text" placeholder="街道、楼牌号" />
        </li>
    </ul>
    <div class="new-addr-alone">
        <img class="no-set" src="/images/address_gpubs_add/checkbox_no_44_icon.png" id="default-btn" data-flag="0">
        <span>是否设为默认地址</span>
    </div>
    <div class="remind">提醒：每次下单时会优先选择该地址，发团时请根据实际情况确认哦！</div>
    <a class="push-btn" id="push-btn" href="#">保存</a>
</div>