<?php
use yii\helpers\Html;
use mobile\modules\temp\assets\TempAsset;

TempAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@mobile/views/layouts/global.php'); ?>
<?= $content ?>
<?php $this->endContent(); ?>
