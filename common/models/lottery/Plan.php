<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午3:36
 */

namespace common\models\lottery;

use common\ActiveRecord\LotteryPlanAR;

/**
 * Class Plan
 * @package common\models\lottery
 * @property $AR LotteryPlanAR
 */
class Plan extends Object
{
    /**
     * @param $name
     * @return static
     * @throws \RuntimeException
     */
    public static function getInstanceByName($name)
    {
        if($ar = LotteryPlanAR::findOne(['name' => $name])){
            return new static(['ar' => $ar]);
        }
        throw new \RuntimeException('计划名错误');
    }

    /**
     * 计划是否已运行
     * @return bool
     */
    public function isExpired()
    {
        return $this->AR->status === LotteryPlanAR::STATUS_FINISH;
    }
}