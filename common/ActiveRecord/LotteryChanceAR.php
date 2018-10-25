<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-30
 * Time: 下午5:07
 */

namespace common\ActiveRecord;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * 抽奖机会
 * Class LotteryChance
 * @package common\ActiveRecord
 * @property $id
 * @property $custom_user_id
 * @property $account
 * @property $plan_id
 * @property $plan_item_id
 * @property $total_fee
 * @property $plan_total_fee
 * @property $chance
 * @property $created
 */
class LotteryChanceAR extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created'
                ],
                'value' => function(){
                    return new Expression('now()');
                }
            ]
        ];
    }

    public static function tableName()
    {
        return '{{%lottery_chance}}';
    }
}