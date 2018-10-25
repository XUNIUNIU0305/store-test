<?php
$this->params = ['js' => 'js/register.js','css'=>'css/register.css'];
$this->title = '九大爷平台 - 会员注册';
?>
<style type="text/css">
    main.container {
        background-color: #fff;
    }
</style>
<!--main container-->
<main class="container">
    <!--index-->
    <div class="mobile-register-update">
        <div class="logo"></div>
        <div class="hr-split"></div>
        <ul class="register-form">
            <li>
                <label for="registerCode">注册码</label>
                <input type="text" id="registerCode" maxlength="9" placeholder="请输入注册码">
            </li>
            <li>
                <label for="pwd">设置密码</label>
                <input type="password" id="pwd" maxlength="20" placeholder="请设置新密码">
            </li>
            <li>
                <label for="pwd_confirm">确认密码</label>
                <input type="password" id="pwd_confirm" maxlength="20" placeholder="请确认密码">
            </li>
            <li>
                <label for="location">选择地区</label>
                <div class="cascade-location">
                    <span>
                        <select id="selProvince">
                        	<option value="-1">请选择</option>
                        </select>
                    </span>
                    <span>
                        <select id="selCity">
                        	<option value="-1">请选择</option>
                        </select>
                    </span>
                    <span>
                        <select id="selDistrict">
                        	<option value="-1">请选择</option>
                        </select>
                    </span>
                </div>
            </li>
            <li>
                <label for="email">邮箱地址</label>
                <input type="text" id="email" placeholder="请输入邮箱">
            </li>
            <li class="mobile-input">
                <label for="mobile">手机号</label>
                <input type="text" id="mobile" maxlength="11" placeholder="请输入手机号">
                <a href="javascript:void(0)" class="valid-btn J_get_verify_sms">获取验证码</a>
            </li>
            <li>
                <label for="valid">验证码</label>
                <input type="text" id="valid" maxlength="6" placeholder="请输入验证码">
            </li>
        </ul>
        <a href="javascript:void(0)" class="register-btn" id="J_register_submit">立即注册</a>
    </div>
</main>
