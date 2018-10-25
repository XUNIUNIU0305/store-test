<?php
use yii\helpers\Html;
use mobile\assets\GlobalAsset;

GlobalAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="yes" name="apple-touch-fullscreen" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no" />
    <meta name="x5-fullscreen" content="true">
	<meta name="full-screen" content="yes">

    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <?= $content ?>

<?php $this->endBody() ?>
<div style="display:none;">
<script src="https://s13.cnzz.com/z_stat.php?id=1262335565&web_id=1262335565"></script>
</div>
</body>
</html>
<?php $this->endPage() ?>
