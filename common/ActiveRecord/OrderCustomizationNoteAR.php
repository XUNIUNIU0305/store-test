<?php
/**
 * 定制订单留言表
 */

namespace common\ActiveRecord;


use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class OrderCustomizationNoteAR
 * @package common\ActiveRecord
 * @property int $order_customization_id
 */
class OrderCustomizationNoteAR extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created'
                ],
                'value' => new Expression('NOW()')
            ]
        ];
    }

    public static function tableName()
    {
        return '{{%order_customization_note}}';
    }
}