<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessAreaLevelAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_area_level}}';
    }
}
