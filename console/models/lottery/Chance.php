<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-30
 * Time: 下午5:08
 */

namespace console\models\lottery;

use common\ActiveRecord\LotteryChanceItemAR;

/**
 * Class Chance
 * @package console\models\lottery
 * @property $AR LotteryChanceAR
 * @property $id
 * @property $custom_user_id
 * @property $account
 * @property $plan_id
 * @property $plan_item_id
 * @property $total_fee
 * @property $chance
 * @property $created
 */
class Chance extends \common\models\lottery\Chance
{
    /**
     * @var ChanceItem[]
     */
    private $items = [];

    public function init()
    {
        for ($i=0; $i<$this->AR->chance; $i++){
            $item = new LotteryChanceItemAR;
            $item->chance_id = $this->id;
            $item->total_fee = $this->plan_total_fee;
            $item->insert(false);
            $this->items[] = new ChanceItem(['ar' => $item]);
        }
    }

//    private $total_price;
//
//    /**
//     * 获取本用户当前活动总消费
//     * @return false|null|string
//     */
//    public function getTotalPrice()
//    {
//        if($this->total_price === null){
//            $this->total_price = LotteryChanceAR::find()
//                ->select(['sum(total_fee) total_price'])
//                ->where(['plan_id' => $this->plan_id])
//                ->andWhere(['custom_user_id' => $this->custom_user_id])
//                ->scalar();
//        }
//        return $this->total_price;
//    }

    /**
     * 中奖
     * @param Prize $prize
     */
    public function winning(Prize $prize)
    {
        //打乱数组避免中奖拍后
        shuffle($this->items);
        /** @var ChanceItem $item */
        $item = array_shift($this->items);
        $item->setPrize($prize);
    }

    /**
     * 是否剩余抽奖机会
     * @return bool
     */
    public function hasChance()
    {
        return count($this->items) > 0;
    }
}