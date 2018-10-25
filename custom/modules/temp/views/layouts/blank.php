<?php
use custom\modules\temp\assets\BlankAsset;

BlankAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@custom/views/layouts/global.php'); ?>

    <?= $content ?>

<?php $this->endContent(); ?>
