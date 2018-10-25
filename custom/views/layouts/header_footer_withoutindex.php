<?php
use custom\assets\HeaderFooterWithoutindexAsset;

HeaderFooterWithoutindexAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@custom/views/layouts/header_footer.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
<div class="unindex-other-top-nav">
    <div class="logo-box">
        <a href="/">
            <img src="/images/logo_2.png">
            <img src="/images/yaoniyiqi_img.jpg">
        </a>
    </div>
    <!-- navigation -->
    <div class="search-nav">
        <ul class="list-inline">
            <li class="actived">
                <a href="/">首页</a>
            </li>
            <li>
                <a target="_blank" href="/membrane">特供车膜</a>
            </li>
            <li>
                <a target="_blank" href="/quality/quality-search/index">质保查询</a>
            </li>
            <li>
                <a target="_blank" href="/shop?id=231">门店优选</a>
            </li>
        </ul>
        <div class="pull-right hidden">
            <a href="#">
                <img src="/images/custom/index_new/05-3-广告位.png" alt="">
            </a>
        </div>
    </div>
</div>