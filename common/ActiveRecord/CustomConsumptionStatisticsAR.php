<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomConsumptionStatisticsAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%custom_consumption_statistics}}';
    }
}
