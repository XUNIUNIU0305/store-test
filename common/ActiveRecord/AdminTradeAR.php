<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminTradeAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_trade}}';
    }
}
