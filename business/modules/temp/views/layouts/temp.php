<?php
use business\modules\temp\assets\TempAsset;

TempAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@business/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
