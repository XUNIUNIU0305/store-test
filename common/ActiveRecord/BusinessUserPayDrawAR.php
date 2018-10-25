<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessUserPayDrawAR extends ActiveRecord{
    
    public static function tableName(){
        return '{{%business_user_pay_draw}}';
    }
}
