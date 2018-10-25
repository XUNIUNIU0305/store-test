<?php
use admin\modules\nanjing\assets\NanjingAsset;
NanjingAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@admin/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
