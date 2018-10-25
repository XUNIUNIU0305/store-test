<?php
use admin\modules\activity\assets\SiteAsset;

SiteAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@admin/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
