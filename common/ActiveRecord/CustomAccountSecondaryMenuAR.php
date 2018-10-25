<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomAccountSecondaryMenuAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_account_secondary_menu}}';
    }
}
