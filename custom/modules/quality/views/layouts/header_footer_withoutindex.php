<?php
use yii\helpers\Html;
use custom\modules\quality\assets\HeaderFooterWithoutindexAsset;

HeaderFooterWithoutindexAsset::register($this)->addFiles($this);
?>

<?php $this->beginContent('@custom/modules/quality/views/layouts/header_footer.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>