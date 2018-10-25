<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class NanjingGatewayDepositAR extends ActiveRecord{

    public static function tableName(){
        return '{{%nanjing_gateway_deposit}}';
    }
}
