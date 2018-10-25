<?php
use yii\helpers\Html;
use supply\assets\IndexAsset;

IndexAsset::register($this);
?>
<?php $this->beginContent('@supply/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
