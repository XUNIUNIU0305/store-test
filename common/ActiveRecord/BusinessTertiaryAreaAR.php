<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessTertiaryAreaAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_tertiary_area}}';
    }
}
