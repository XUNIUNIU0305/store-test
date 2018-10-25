<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminTradePartnerAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_trade_partner}}';
    }
}
