<?php

use supply\assets\PrintAsset;

PrintAsset::register($this)->addFiles($this);?>
<?php $this->beginContent('@supply/views/layouts/global.php'); ?>

            <?= $content ?>

<?php $this->endContent(); ?>
