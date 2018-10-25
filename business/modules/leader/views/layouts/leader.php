<?php
use business\modules\leader\assets\LeaderAsset;

LeaderAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@business/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
