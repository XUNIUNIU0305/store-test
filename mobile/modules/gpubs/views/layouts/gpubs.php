<?php
use yii\helpers\Html;
use mobile\modules\gpubs\assets\GpubsAsset;

GpubsAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@mobile/views/layouts/main.php'); ?>
<?= $content ?>
<?php $this->endContent(); ?>
