<?php
/**
 * @var $this \yii\web\View
 */

$this->title = '';
$asset = \mobile\modules\lottery\assets\LotteryAsset::register($this);
$asset->js[] = 'js/record.js';
$asset->css[] = 'css/record.css';
?>
<div class="record-container">
    <div class="record-board">
        <div class="title"></div>
        <!-- nav -->
        <ul>
            <li data-tab="#records" class="active">中奖记录</li>
            <li data-tab="#tools">我的工具</li>
        </ul>
        <!-- cnt -->
        <div class="record-content">
            <!-- 中奖记录 -->
            <div class="content" id="records">
                <!-- 没有奖品 -->
                <div class="empty">
                    <p>唉 ╭(╯^╰)╮ 你怎么没有奖品呢~<br>快去抽奖吧！</p>
                    <a href="<?= \yii\helpers\Url::toRoute(['game/index'])?>" class="btn"></a>
                </div>
            </div>
            <!-- 我的工具 -->
            <div class="content hidden" id="tools">
                <div class="col-3 header">
                    <div class="time">获得时间</div>
                    <div class="desctiption">购买产品</div>
                    <div class="img">获得工具</div>
                </div>
                <div class="scroll" id="tool-box"></div>
            </div>
        </div>
    </div>
</div>