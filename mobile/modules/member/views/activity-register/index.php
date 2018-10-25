<?php
$this->params = ['css' => 'css/activity-register.css', 'js' => 'js/activity-register.js'];
$this->title = '用户注册';
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
        <div class="title">活动专用注册页</div>
        <ul class="register-form">
            <li class="mobile-input">
                <label for="mobile">手机号</label>
                <input type="tel" id="mobile" maxlength="11" placeholder="请输入手机号">
            </li>
            <li>
                <label for="pwd">设置密码</label>
                <input type="password" id="pwd" maxlength="20" placeholder="请设置密码">
            </li>
            <li>
                <label for="pwd_confirm">确认密码</label>
                <input type="password" id="pwd_confirm" maxlength="20" placeholder="请确认密码">
            </li>
            <li>
                <label for="valid">验证码</label>
                <input type="text" id="valid" maxlength="6" placeholder="请输入验证码">
                <a href="javascript:void(0)" class="valid-btn J_get_verify_sms">获取验证码</a>
            </li>
        </ul>
        <a href="javascript:void(0)" class="register-btn" id="J_register_submit">立即注册</a>
        <a href="/member/login" class="jump-index">已有账号？点击登录</a>
    </div>
</main>
