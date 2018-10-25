<?php
use custom\modules\account\assets\ArticleAsset;

ArticleAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@custom/views/layouts/global.php'); ?>

            <?= $content ?>

<?php $this->endContent(); ?>
