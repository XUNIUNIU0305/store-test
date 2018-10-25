<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessAreaConsumptionStatisticsAR extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%business_area_consumption_statistics}}';
    }
}