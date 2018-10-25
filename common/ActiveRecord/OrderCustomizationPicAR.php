<?php
/**
 * 定制订单图片
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class OrderCustomizationPicAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_customization_pic}}';
    }
}