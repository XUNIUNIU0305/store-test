<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomUserPayNonTransactionAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_user_pay_non_transaction}}';
    }
}
