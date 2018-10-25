<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-30
 * Time: 下午3:20
 */

namespace console\models\lottery;


use common\ActiveRecord\LotteryChancePrizeAR;
use common\ActiveRecord\LotteryPlanAR;
use common\ActiveRecord\LotteryPlanProductAR;
use common\ActiveRecord\LotteryPrizeAR;

/**
 * Class Plan
 * @package console\models\lottery
 * @property $id
 * @property $name
 * @property $start_date
 * @property $end_date
 * @property $money_limit
 * @property $status
 */
class Plan extends \common\models\lottery\Plan
{
    /**
     * 获取计划商品列表
     * @return PlanItemList
     */
    public function getItemList()
    {
        $items = LotteryPlanProductAR::find()
            ->where(['plan_id' => $this->id])
            ->all();
        return new PlanItemList([
            'items' => $items,
            'plan' => $this
        ]);
    }

    /**
     * 更新计划状态为完成
     */
    public function finish()
    {
        $this->AR->status = LotteryPlanAR::STATUS_FINISH;
        $this->AR->update(false);
    }

    /**
     * 活动时间是否已结束
     * @return bool
     */
    public function isEnd()
    {
        return $this->end_date <= date('Y-m-d H:i:s');
    }

    /**
     * 查询中奖状态
     * @return array|\yii\db\ActiveRecord[]
     */
    public function queryLotteryStatus()
    {
        $prizeList = LotteryPrizeAR::find()
            ->where(['plan_id' => $this->id])
            ->asArray()->all();

        foreach ($prizeList as &$prize){
            $prize['now'] = LotteryChancePrizeAR::find()
                ->select(['count(1) num'])
                ->where(['prize_id' => $prize['id']])
                ->scalar();
        }

        return $prizeList;
    }
}