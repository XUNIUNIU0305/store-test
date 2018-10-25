<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class OrderCustomRecordAR extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%order_custom_record}}";
    }
}