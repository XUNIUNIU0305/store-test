<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class OrderProductRecordAR extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%order_product_record}}";
    }
}