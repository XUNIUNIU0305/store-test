<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-30
 * Time: 下午3:01
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * 抽奖计划
 * Class LotteryPlanAR
 * @package common\ActiveRecord
 * @property $id
 * @property $name
 * @property $start_date
 * @property $end_date
 * @property $status
 */
class LotteryPlanAR extends ActiveRecord
{
    const STATUS_DEFAULT = 0;
    const STATUS_FINISH = 1;

    public static function tableName()
    {
        return '{{%lottery_plan}}';
    }
}