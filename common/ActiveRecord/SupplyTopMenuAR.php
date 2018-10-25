<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class SupplyTopMenuAR extends ActiveRecord{

    public static function tableName(){
        return '{{%supply_top_menu}}';
    }
}
