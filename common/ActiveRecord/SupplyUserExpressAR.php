<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-23
 * Time: 上午11:55
 */

namespace common\ActiveRecord;


use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class SupplyUserExpressAR extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created',
                ],
                'value' => function () {
                    return new Expression('now()');
                },
            ]
        ];
    }

    public static function tableName()
    {
        return '{{%supply_user_express}}';
    }
}