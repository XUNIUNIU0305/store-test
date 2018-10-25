<?php
use admin\modules\site\assets\SiteAsset;

SiteAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@admin/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
