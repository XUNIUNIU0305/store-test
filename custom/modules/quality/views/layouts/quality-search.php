<?php
use yii\helpers\Html;
use custom\modules\quality\assets\QualitySearchAsset;

QualitySearchAsset::register($this)->addFiles($this);
?>

<?php $this->beginContent('@custom/modules/quality/views/layouts/header_footer_search.php'); ?>
<div class="quality-header">
    <div class="header-layout">
        <a href="/quality/quality-search/index">九大爷质保查询系统</a>
    </div>
</div>
<?= $content ?>
<!-- footer -->
<footer class="apx-footer container-fluid text-center" style="background-color: transparent;">
    <a href="http://www.miitbeian.gov.cn" target="_blank">苏ICP备16057447号-2</a>&nbsp;&nbsp;|&nbsp;
    <span>创智汇（苏州）电子商务有限公司版权所有</span>&nbsp;&nbsp;|&nbsp;
    <span>电话：400-0318-119   &nbsp;&nbsp;|&nbsp;  邮箱：<a href="mailto: kf@9daye.com.cn ">kf@9daye.com.cn </a></span>
</footer>
<?php $this->endContent(); ?>
