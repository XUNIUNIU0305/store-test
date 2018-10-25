<?php
$this->params = ['js' => 'js/account-login.js','css'=>'css/login.css'];
$this->title = '九大爷平台 - 会员登录';
?>
<style type="text/css">
    main.container {
        background-image: -webkit-linear-gradient( 128deg, rgb(229,57,53) 0%, rgb(255,145,0) 100%);
    }
</style>
<!--main container-->
<main class="container">
    <div class="login-new">
        <div class="wechat-tip hidden">
            <p>此微信号未绑定（请至电脑版绑定微信登入）</p>
        </div>
        <div class="wechat-login-new">
            <div class="logo">
                <img src="/images/login_new/login_logo.png">
            </div>
            <div class="form-group">
                <label for="name"><img src="/images/login_new/usename_icon.png"></label>
                <input id="username" type="text" placeholder="请输入平台账户">
            </div>
            <div class="form-group">
                <label for="pwd"><img src="/images/login_new/password_icon.png"></label>
                <input id="password" type="password" placeholder="请输入账户密码">
            </div>
            <div class="error-msg in">账户不存在或密码不正确！</div>
            <div class="login-btn">
                <a href="/member/register" class="btn btn-register">快速注册</a>
                <a class="btn btn-login J_login_btn">账号登录</a>
            </div>
            <div class="wechat-login">
                <p>或者，您可以</p>
                <a href="/member/login/wechat" class="wechat-btn">
                    <img src="/images/login_new/wechat_logo.png" alt="wechat_login"> 微信一键登录
                </a>
            </div>
            <div class="mobile">
                <a href="tel:4000318119">联系电话：400-0318-119</a>
            </div>
        </div>
    </div>
</main>
