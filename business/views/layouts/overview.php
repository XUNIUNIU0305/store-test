<?php
use business\assets\OverviewAsset;

OverviewAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@business/views/layouts/global.php'); ?>

<?=$content ?>

<?php $this->endContent(); ?>
