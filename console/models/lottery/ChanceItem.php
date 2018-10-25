<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午5:17
 */

namespace console\models\lottery;

use common\ActiveRecord\LotteryChanceItemAR;
use common\ActiveRecord\LotteryChancePrizeAR;

/**
 * Class ChanceItem
 * @package console\models\lottery
 * @property $AR LotteryChanceItemAR
 * @property $total_fee
 */
class ChanceItem extends \common\models\lottery\ChanceItem
{
    public function setPrize(Prize $prize)
    {
        $this->AR->result = LotteryChanceItemAR::RESULT_WINNING;
        $this->AR->update(false);

        /**
         * 记录奖品
         */
        $chancePrize = new LotteryChancePrizeAR;
        $chancePrize->chance_item_id = $this->id;
        $chancePrize->custom_user_id = $this->getChance()->custom_user_id;
        $chancePrize->prize_id = $prize->id;
        $chancePrize->type = $prize->type;
        $chancePrize->name = $prize->name;
        $chancePrize->insert(false);
    }
}