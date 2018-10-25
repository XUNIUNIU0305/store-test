<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomUserTradeGpubsAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_user_trade_gpubs}}';
    }
}
