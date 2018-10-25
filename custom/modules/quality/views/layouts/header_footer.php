<?php
use yii\helpers\Html;
use custom\modules\quality\assets\HeaderFooterAsset;

HeaderFooterAsset::register($this)->addJs(isset($this->params['js']) ? $this->params['js'] : null)->addCss(isset($this->params['css']) ? $this->params['css'] : null);
?>

<?php $this->beginContent('@custom/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
