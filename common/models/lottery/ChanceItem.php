<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午5:17
 */

namespace common\models\lottery;

use common\ActiveRecord\LotteryChanceItemAR;
/**
 * Class ChanceItem
 * @package common\models\lottery
 * @property $id
 * @property $status
 * @property $result
 * @property $open_date
 */
class ChanceItem extends Object
{
    /**
     * @param $id
     * @return static
     */
    public static function getInstanceById($id)
    {
        if($ins = LotteryChanceItemAR::findOne($id)){
            return new static([
                'ar' => $ins
            ]);
        }
        throw new \RuntimeException('not found');
    }

    private $chance;
    /**
     * @return Chance
     */
    public function getChance()
    {
        if($this->chance === null){
            $this->chance = Chance::getInstanceById($this->chance_id);
        }
        return $this->chance;
    }

    private $prize;

    /**
     * @return mixed
     */
    public function getPrize()
    {
        if($this->result === LotteryChanceItemAR::RESULT_WINNING && $this->prize === null){
            $this->prize = ChancePrize::getInstanceByItemId($this->id);
        }
        return $this->prize;
    }
}