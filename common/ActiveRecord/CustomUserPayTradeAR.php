<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomUserPayTradeAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_user_pay_trade}}';
    }
}
