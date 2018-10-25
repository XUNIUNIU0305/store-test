<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomUserTradeZodiacAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_user_trade_zodiac}}';
    }
}
