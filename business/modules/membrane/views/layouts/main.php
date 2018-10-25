<?php
/**
 * @var $this \yii\web\View
 */

\business\modules\membrane\assets\MembraneAsset::register($this);
?>
<?php $this->beginContent('@business/views/layouts/global.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
