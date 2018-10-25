<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessUserPayLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_user_pay_log}}';
    }
}
