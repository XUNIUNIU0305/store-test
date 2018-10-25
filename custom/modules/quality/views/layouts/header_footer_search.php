<?php
use yii\helpers\Html;
use custom\modules\quality\assets\HeaderFooterSearchAsset;

HeaderFooterSearchAsset::register($this)->addJs(isset($this->params['js']) ? $this->params['js'] : null)->addCss(isset($this->params['css']) ? $this->params['css'] : null);
?>

<?php $this->beginContent('@custom/modules/quality/views/layouts/header_footer_withoutindex.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
