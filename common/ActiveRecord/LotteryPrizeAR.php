<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-30
 * Time: 下午3:02
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * 奖品
 * Class LotteryPrizeAR
 * @package common\ActiveRecord
 * @property $id
 * @property $plan_id
 * @property $name
 * @property $price
 * @property $num
 * @property $prize_limit
 * @property $type
 */
class LotteryPrizeAR extends ActiveRecord
{
    const STATUS_LINE_DOWN = 10;    //线下
    const STATUS_LINE_ON = 20;      //线上（代金券）

    public static function tableName()
    {
        return '{{%lottery_prize}}';
    }
}