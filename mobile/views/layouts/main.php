<?php
use yii\helpers\Html;
use mobile\assets\BaseAsset;
BaseAsset::register($this)->addJs(isset($this->params['js']) ? $this->params['js'] : null)->addCss(isset($this->params['css']) ? $this->params['css'] : null);
?>
<?php $this->beginContent('@mobile/views/layouts/global.php'); ?>

<!--bottom nav-->
<nav class="bottom-nav J_footer_menu">
    <a href="/" class="home active">
        <i></i><span>首页</span>
    </a>
    <a href="/shopping/index" class="brand">
        <i></i><span>购物车</span>
    </a>
    <a href="/member/index" class="cart">
        <i></i><span>个人中心</span>
    </a>
    <a href="/member/login/index" class="account" id="J_login_btn">
        <i></i><span>登录</span>
    </a>
</nav>

<?= $content ?>
<?php $this->endContent(); ?>
