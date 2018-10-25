<?php
use yii\helpers\Html;
use admin\assets\OverviewAsset;

OverviewAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@admin/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
