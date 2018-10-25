<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午3:41
 */

namespace common\models\lottery;


use common\ActiveRecord\LotteryPrizeAR;

/**
 * Class Prize
 * @package common\models\lottery
 * @property $id
 * @property $plan_id
 * @property $name
 * @property $price
 * @property $num
 * @property $prize_limit
 * @property $type
 */
class Prize extends Object
{
    /**
     * @param $id
     * @return array
     */
    public static function getListByPlanId($id)
    {
        $list = LotteryPrizeAR::find()
            ->where(['plan_id' => $id])
            ->orderBy('price ASC')
            ->all();
        foreach ($list as &$item) {
            $item = new static(['ar' => $item]);
        }
        return $list;
    }

    /**
     * @param $id
     * @return static
     */
    public static function getInstanceById($id)
    {
        if(!$ins = LotteryPrizeAR::findOne($id)) throw new \RuntimeException('奖品不存在');
        return new static([
            'ar' => $ins
        ]);
    }
}