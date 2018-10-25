<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午2:55
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * Class LotteryChancePrizeAR
 * @package common\ActiveRecord
 * @property $id
 * @property $custom_user_id
 * @property $chance_item_id
 * @property $prize_id
 * @property $type
 * @property $status
 * @property $name
 * @property $created
 */
class LotteryChancePrizeAR extends ActiveRecord
{
    const STATUS_DEFAULT = 0;   //默认
    const STATUS_USED = 1;      //已使用

    const TYPE_DEFAULT = 10;    //默认
    const TYPE_VOUCHER = 20;    //代金券

    public static function tableName()
    {
        return '{{%lottery_chance_prize}}';
    }
}