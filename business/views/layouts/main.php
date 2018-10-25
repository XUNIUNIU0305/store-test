<?php
use business\assets\MainAsset;

MainAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@business/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
