<?php
/**
 * 定制订单表
 */
namespace common\ActiveRecord;


use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\AttributeBehavior;

/**
 * @property int $id
 * @property int $order_number
 * @property string $upload_date
 * @property string $update_date
 * @property string $reject_date
 * @property string $created_date
 * @property string $cancel_date
 * Class OrderCustomizationAR
 * @package common\ActiveRecord
 */
class OrderCustomizationAR extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_date'
                ],
                'value' => function(){
                    return new Expression('now()');
                }
            ]
        ];
    }

    public static function tableName(){
        return '{{%order_customization}}';
    }
}