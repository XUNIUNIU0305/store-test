<?php
/**
 * @var $this \yii\web\View
 */
$this->title = '九大爷平台 - 会员登录';
?>

<!--main container-->
<main class="container">
    <div class="wechat-tip hidden">
        <p>此微信号未绑定（请至电脑版绑定微信登入）</p>
    </div>
    <div class="wechat-login">
        <form action="/login/login" method="post">
            <div class="logo">
                <img src="/images/logo.png">
            </div>
            <!-- <h3>九大爷登录</h3> -->
            <div class="form-group">
                <label for="name"><img src="/images/login/account.png"></label>
                <input id="username" name="account" type="text" placeholder="请输入平台账户">
            </div>
            <div class="form-group">
                <label for="pwd"><img src="/images/login/password.png"></label>
                <input id="password" name="passwd" type="password" placeholder="请输入账户密码">
            </div>
            <div class="error-msg in">账户不存在或密码不正确！</div>
            <button type="submit">提交</button>
            <div class="wechat-login">
                <p>或者，您可以</p>
                <a href="/member/login/wechat" class="wechat-btn">
                    <img src="/images/login/wechat.png" alt="wechat_login"> 微信一键登录
                </a>
            </div>
        </form>
    </div>

</main>
