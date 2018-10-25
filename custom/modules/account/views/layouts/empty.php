<?php
use yii\helpers\Html;
use custom\modules\account\assets\EmptyAsset;

EmptyAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@custom/views/layouts/header_footer_search.php'); ?>

            <?= $content ?>

<?php $this->endContent(); ?>
