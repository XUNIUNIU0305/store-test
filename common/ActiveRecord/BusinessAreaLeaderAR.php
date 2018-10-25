<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessAreaLeaderAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_area_leader}}';
    }
}
