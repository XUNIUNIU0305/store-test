<?php
use business\modules\quality\assets\QualityAsset;
QualityAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@business/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
