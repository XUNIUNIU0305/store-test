<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-30
 * Time: 下午1:23
 */

namespace console\controllers;

use console\models\lottery\Plan;
use console\models\lottery\PlanItemList;
use console\models\lottery\PrizePool;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * 抽奖
 * Class LotteryController
 * @package console\controllers
 */
class LotteryController extends Controller
{
    /**
     * 运行抽奖)
     * @param $plan string 计划名
     * @return int
     */
    public function actionIndex($plan)
    {
        ini_set('memory_limit', '256M');
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $plan = Plan::getInstanceByName($plan);
            if(!$plan->isEnd()){
                $this->stderr("活动时间未结束,　不能直接执行\n", Console::FG_RED);
                return 1;
            }
            if($plan->isExpired()){
                $this->stderr("计划已完成,　无法重新运行\n", Console::FG_RED);
                return 1;
            }

            $planItemList = $plan->getItemList();
            $chance = $planItemList->generateChance();

            $planItemList = null;
            $pool = PrizePool::getPoolByPlan($plan, $chance);
            $pool->lottery();
            $plan->finish();
            $transaction->commit();
            $this->stdout("抽奖完成\n", Console::FG_GREEN);
            $max = memory_get_peak_usage(true) / (1024 * 1024);
            $this->stdout(sprintf("内存峰值 %.2fM\n", $max));
            return 0;
        } catch (\Exception $e){
            $transaction->rollBack();
            \Yii::error($e->getMessage(), 'Lottery');
            $this->stderr($e->getMessage() . "\n", Console::FG_RED);
            return 1;
        }
    }

    /**
     * 实时消费统计
     * @param $plan
     * @return int
     */
    public function actionQuery($plan)
    {
        $plan = Plan::getInstanceByName($plan);
        $items = $plan->getItemList()->queryUserChance();
        $this->stdout("\n  当前消费统计\n");
        foreach ($items as $item){
//            if(empty($item)) continue;
            $this->stdout("------------------------------------------------------------\n");
            $this->stdout(sprintf("  用户: %s, 当前消费额: %.2f, 活动总消费: %.2f, 获得抽奖次数: %d \n", $item['account'], $item['fee'], $item['total_fee'] ,$item['num']));
        }
        $this->stdout("============================================================\n");
        $this->stdout(sprintf("  当前总消费: ￥%.2f\n", array_sum(array_column($items, 'fee'))));
        $this->stdout(sprintf("  获得抽奖次数: %d次\n", array_sum(array_column($items, 'num'))));
        $this->stdout("\n");
        return 0;
    }

    /**
     * 查看计划中奖状态
     * @param $plan
     * @return int
     */
    public function actionStatus($plan)
    {
        $plan = Plan::getInstanceByName($plan);

        foreach ($plan->queryLotteryStatus() as $item){
            $this->stdout("------------------------------------------------------------\n");
            $color = $item['num'] == $item['now'] ? Console::FG_GREEN : Console::FG_RED;
            $this->stdout(sprintf("奖品: %s, 数量: %d, 中奖限制: %.2f; 当前中奖数量: %d\n", $item['name'], $item['num'], $item['prize_limit'], $item['now']), $color);
        }
        $this->stdout("\n");
        return 0;
    }

    public function actionFix()
    {
        $items = PlanItemList::queryFix();
        $items = PlanItemList::parseItems($items, 500);
        $this->stdout("\n  当前修复订单\n");
        foreach ($items as $item){
//            if(empty($item)) continue;
            $this->stdout("------------------------------------------------------------\n");
            $this->stdout(sprintf("  用户: %s, 当前消费额: %.2f, 活动总消费: %.2f, 获得抽奖次数: %d \n", $item['account'], $item['fee'], $item['total_fee'] ,$item['num']));
        }
        $this->stdout("============================================================\n");
        $this->stdout(sprintf("  当前总消费: ￥%.2f\n", array_sum(array_column($items, 'fee'))));
        $this->stdout(sprintf("  获得抽奖次数: %d次\n", array_sum(array_column($items, 'num'))));
    }
}