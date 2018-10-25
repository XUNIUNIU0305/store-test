<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessSecondaryAreaAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_secondary_area}}';
    }
}
