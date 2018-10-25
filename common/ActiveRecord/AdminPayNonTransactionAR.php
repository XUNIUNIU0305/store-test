<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminPayNonTransactionAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_pay_non_transaction}}';
    }
}
