<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class NanjingPayBalanceAR extends ActiveRecord{

    public static function tableName(){
        return '{{%nanjing_pay_balance}}';
    }
}
