<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class SupplySecondaryMenuAR extends ActiveRecord{

    public static function tableName(){
        return '{{%supply_secondary_menu}}';
    }
}
