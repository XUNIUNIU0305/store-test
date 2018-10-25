<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class SupplyUserAR extends ActiveRecord{

    public static function tableName(){
        return '{{%supply_user}}';
    }
}
