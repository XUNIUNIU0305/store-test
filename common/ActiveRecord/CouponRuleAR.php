<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class CouponRuleAR extends ActiveRecord{


    public static function tableName(){
        return '{{%coupon_rule}}';
    }
}
