<?php
use yii\helpers\Html;
use custom\modules\quality\assets\QualitySearchAsset;

QualitySearchAsset::register($this)->addFiles($this);
?>

<?php $this->beginContent('@custom/modules/quality/views/layouts/header_footer_search.php'); ?>
<?= $content ?>

<?php $this->endContent(); ?>
