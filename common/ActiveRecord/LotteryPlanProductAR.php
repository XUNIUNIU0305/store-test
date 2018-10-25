<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-30
 * Time: 下午4:01
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

/**
 * Class LotteryPlanProductAR
 * @package common\ActiveRecord
 * @property $id
 * @property $name
 * @property $brand_id
 * @property $product_id
 * @property $plan_id
 * @property $money_limit
 */
class LotteryPlanProductAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%lottery_plan_product}}';
    }
}