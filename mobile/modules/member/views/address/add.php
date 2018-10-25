<?php
$this->params = ['js' => 'js/address-add.js','css'=>'css/address.css'];
$this->title = '九大爷平台 - 新增地址';
?>
<!--main container-->
<main class="container">
    <!--address-->
    <div class="wechat-address-container">
        <ul class="address-form" id="J_tpl_address">
            <li>
                <label for="name">收货人：</label>
                <input type="text" id="name">
            </li>
            <li>
                <label for="mobile">手机号：</label>
                <input type="number" id="mobile" oninput="if(value.length>5)value=value.slice(0,11)">
            </li>
            <li>
                <label for="postal_code">邮政编码：</label>
                <input type="number" id="postal_code" oninput="if(value.length>5)value=value.slice(0,6)">
            </li>
            <li class="select">
                <label for="area">所在区域：</label>
                <select id="selProvince">
                    <option value="">省份</option>
                </select>
                <select id="selCity">
                    <option value="">城市</option>
                </select>
                <select id="selDistrict">
                    <option value="">区/县</option>
                </select>
            </li>
            <li>
                <label for="address_detail">详细地址：</label>
                <textarea id="address_detail" rows="3" placeholder="请填写详细地址"></textarea>
            </li>
            <li class="split"></li>
            <li>
                <label>是否默认</label>
                <div class="switch-btn" id="setDefault">
                    <input type="checkbox"/>
                    <div><div></div></div>
                </div>
            </li>
        </ul>
    </div>
    <!--bottom btn-->
    <a href="#" class="btn-block-bottom  J_save_address">保存修改</a>
</main>