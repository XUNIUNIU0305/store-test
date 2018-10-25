<?php
/**
 * @var $this \yii\web\View
 */

$this->title = '';
$asset = \mobile\modules\lottery\assets\LotteryAsset::register($this);
$asset->js[] = 'js/game.js';
$asset->css[] = 'css/game.css';
?>

<div class="game-container">
    <ul class="tool-nav" id="tool-nav">
    </ul>
    <div class="figure-container">
        <div class="figure after hidden">
            <img id="man-after" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWNgYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg==">
        </div>
        <div class="figure before">
            <img id="man-before" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWNgYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg==">
        </div>
        <div class="ani-smoke">
            <i></i>
            <i></i>
            <i></i>
            <i></i>
        </div>
        <img class="ani-tool"/>
    </div>
    <a href="javascript:void(0)" class="btn" data-play></a>
    <a href="#" id="btn-open" class="btn-open btn hidden"></a>
</div>
<!-- masks hidden -->
<div class="mask-bg hidden">
    <div class="mask-cnt mask-gift show">
        <div class="title"></div>
        <p>你获得了一个九大爷礼包！!</p>
        <div class="box"></div>
        <div class="timeout"><img src="/images/lottery/timeout.gif" alt=""/></div>
    </div>
</div>
