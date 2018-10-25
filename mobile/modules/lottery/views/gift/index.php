<?php
/**
 * @var $this \yii\web\View
 */

$this->title = '';
$asset = \mobile\modules\lottery\assets\LotteryAsset::register($this);
$asset->js[] = 'js/gift.js';
$asset->css[] = 'css/gift.css';
?>

<div class="gift-container">
    <div class="broadcast-wrap">
        <i>logo</i>
        <div class="scroll-box" id="scroll-box">
            <ul id="notify"></ul>
        </div>
    </div>

    <div class="gift-content" id="container">
        <div class="title"></div>
        <div class="content" id="content">
            <div class="box" id="prize-box"></div>
            <p id="prize-name"></p>
        </div>
        <div class="footer">
            详情请联系九大爷店小二<br>
            <a href="tel:400-0318-119">400-0318-119</a>
        </div>
    </div>

    <div class="btn-box">
        <a href="<?= \yii\helpers\Url::toRoute(['game/index'])?>" class="btn btn-game">继续开奖</a>
        <a href="<?= \yii\helpers\Url::toRoute(['record/index'])?>" class="btn btn-record">中奖记录</a>
    </div>
</div>