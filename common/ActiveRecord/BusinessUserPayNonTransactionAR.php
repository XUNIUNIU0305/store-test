<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessUserPayNonTransactionAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_user_pay_non_transaction}}';
    }
}
