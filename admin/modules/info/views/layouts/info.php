<?php

use admin\modules\info\assets\InfoAsset;
InfoAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@admin/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
