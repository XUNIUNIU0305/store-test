<?php
use yii\helpers\Html;
use mobile\modules\member\assets\MemberAsset;

MemberAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@mobile/views/layouts/main.php'); ?>
<?= $content ?>
<?php $this->endContent(); ?>

