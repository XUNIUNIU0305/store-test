<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-12
 * Time: 下午2:38
 */

namespace common\models\lottery;

use common\ActiveRecord\LotteryChancePrizeAR;

class ChancePrize extends Object
{
    /**
     * @param $id
     * @return static
     */
    public static function getInstanceByItemId($id)
    {
        $ins = LotteryChancePrizeAR::find()
            ->where(['chance_item_id' => $id])
            ->one();
        if(!$ins) throw new \RuntimeException('not found');
        return new static([
            'ar' => $ins
        ]);
    }

    private $prize;

    /**
     * @return mixed
     */
    public function getMainPrize()
    {
        if($this->prize === null){
            $this->prize = Prize::getInstanceById($this->prize_id);
        }
        return $this->prize;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return floatval($this->getMainPrize()->price);
    }
}