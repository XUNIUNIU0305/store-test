<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class NonTransactionDepositAndDrawAR extends ActiveRecord{

    public static function tableName(){
        return '{{%non_transaction_deposit_and_draw}}';
    }
}
