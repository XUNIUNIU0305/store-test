<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessTopAreaAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_top_area}}';
    }
}
