<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomUserTradeOrderAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_user_trade_order}}';
    }
}
