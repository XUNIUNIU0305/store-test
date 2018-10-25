<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class BusinessSecondaryMenuAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_secondary_menu}}';
    }
}
