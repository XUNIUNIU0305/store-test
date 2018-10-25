<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午3:25
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * Class LotteryChanceItemAR
 * @package common\ActiveRecord
 * @property $id
 * @property $chance_id
 * @property $total_fee
 * @property $status
 * @property $result
 */
class LotteryChanceItemAR extends ActiveRecord
{
    const STATUS_DEFAULT = 0;           //未开奖
    const STATUS_OPENED = 1;           //已开奖

    const RESULT_DEFAULT = 0;           //默认(未中奖)
    const RESULT_WINNING = 1;           //已中奖

    public static function tableName()
    {
        return '{{%lottery_chance_item}}';
    }
}