<?php
use yii\helpers\Html;
use custom\modules\quality\assets\QualityAsset;
QualityAsset::register($this)->addFiles($this);
?>
<?php $this->beginContent('@custom/views/layouts/header_footer_search.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>

