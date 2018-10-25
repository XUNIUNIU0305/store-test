<?php
/**
 * @var $this \yii\web\View
 */

$this->title = '';
$asset = \mobile\modules\lottery\assets\LotteryAsset::register($this);
$asset->js[] = 'js/index.js';
$asset->css[] = 'css/index.css';
?>

<!-- interface -->
<div class="index-container">
    <div class="banner"></div>
    <a href="<?=\yii\helpers\Url::to('/temp/brand/v')?>" class="pop">获得机会</a>
    <a href="#game_confirm" data-mask class="btn-entrance">砸脑袋抽奖</a>
    <div class="col-2">
        <a href="#game_rule" data-mask class="btn-rule">游戏规则</a>
        <a href="<?= \yii\helpers\Url::toRoute(['record/index'])?>" class="btn-record">查看抽奖</a>
    </div>
    <div class="footer"></div>
</div>
<!-- masks hidden -->
<div class="mask-bg hidden" data-dismiss>
    <div class="mask-cnt mask-confirm" id="game_confirm">
        <div class="title"></div>
        <p>您确认需要消耗一次砸奖机会进行游戏吗？</p>
        <a class="btn-link" href="<?= \yii\helpers\Url::toRoute(['game/index'])?>">我要玩</a>
        <a href="javascript:void(0);" data-dismiss>一会儿再玩</a>
    </div>
    <div class="mask-cnt mask-rule" id="game_rule">
        <div class="title"></div>
        <div class="scroll">
            <p>
                <h2>1017购物节活动说明</h2>
                1、抽奖：凡在1017当日（0:00—24:00）期间购买指定品牌产品，“时段”每满500元即自动获取活动抽奖券一张，时段内可累加。<br/><br/>

                2、当日参加抽奖活动品牌/产品是：<br/>
                【nanoskin白肤美】 【 比尔尖兵】  【GIGI鼠】 【永泰和】【好顺】 【奔瑞】  【牧宝】 【强力坐垫】 【吾柚】 【大智】 【肖勒】 【万世博3D坐垫】 【御甲脚垫】 【奥和丝圈脚垫】 【3D圣田】 【文丰脚垫】【未未雨刷】 【雅迪/雅楠】 【AIRBus空客净爽】<br/>
                说明：感谢以上品牌提供奖品，当日采购以上品牌产品方有获奖资格。<br/><br/>

                3、奖品分类：<br/>
                车辆类：五菱宏光<br/>
                手机类：Iphone8  华为Mate10<br/>
                电子类：Ipadmini4  空气加湿器 空气净化器 充电宝等<br/>
                用品类：洗车毛巾 行李箱  保温杯等<br/>
                卡券类：1000元/500元汽油卡  电话卡 家乐福卡等<br/>
                平台类：各种面值平台现金抵用券<br/><br/>
                说明：所有价值1000元以上奖品为永久使用权，不需要缴纳个人所得税。<br/><br/>

                4、抽奖时段<br/>
                A.am8:17可开始抽奖0:00-8:0期间获得奖券<br/>
                B.pm12:17可开始抽奖8:00-12:00期间获得奖券<br/>
                C.pm16:17可开始抽奖12:00-16:00期间获得奖券<br/>
                D.pm20:17可开始抽奖16:00-20:00期间获得奖券<br/>
                E.次日am00:17可开始抽奖20:00-24:00期间获得奖券<br/><br/>
                说明：奖券开奖时间截止于2017年10月18日24时，奖品于抽奖后10个工作日内发出，华为mate10、五菱宏光等依可购买到时间为准，预计略有延迟。<br/>
            </p>
        </div>
        <a href="javascript:void(0);" data-dismiss>知道了</a>
    </div>
</div>