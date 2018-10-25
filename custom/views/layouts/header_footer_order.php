<?php
use yii\helpers\Html;
use custom\assets\HeaderFooterOrderAsset;

HeaderFooterOrderAsset::register($this)->addJs(isset($this->params['js']) ? $this->params['js'] : null)->addCss(isset($this->params['css']) ? $this->params['css'] : null);
?>
<?php $this->beginContent('@custom/views/layouts/header_footer_withoutindex.php'); ?>

<!-- <nav class="apx-search-nav navbar navbar-default">
    <div class="container">
        <div class="row">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">
                    <img src="/images/majiang_icon.png" class="img-responsive">
                </a>
            </div>
            <ol class="navbar-cart-progress list-inline navbar-right">
                <li class="past">我的购物车</li>
                <li class="current">核对订单信息</li>
                <li>成功提交订单</li>
            </ol>
        </div>
    </div>
</nav>
 -->
<?= $content ?>

<?php $this->endContent(); ?>
