<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class PaymentMethodAR extends ActiveRecord{

    public static function tableName(){
        return '{{%payment_method}}';
    }
}
