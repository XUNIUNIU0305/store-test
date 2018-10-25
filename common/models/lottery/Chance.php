<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午2:35
 */

namespace common\models\lottery;


use common\ActiveRecord\LotteryChanceAR;

/**
 * Class Chance
 * @package common\models\lottery
 * @property $custom_user_id int
 * @property $id int
 */
class Chance extends Object
{
    /***
     * @param $id
     * @return Chance
     */
    public static function getInstanceById($id)
    {
        if($instance = LotteryChanceAR::findOne($id)){
            return new static([
                'ar' => $instance
            ]);
        }
        throw new \RuntimeException('ID错误');
    }
}