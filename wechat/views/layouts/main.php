<?php
/**
 * @var $this \yii\web\View
 */
use wechat\assets\AppAssets;
use yii\helpers\Html;
?>
<?php $this->beginPage()?>
<!doctype html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody()?>
<?= $content?>
<script src="/assets/bundle.js"></script>
<?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>