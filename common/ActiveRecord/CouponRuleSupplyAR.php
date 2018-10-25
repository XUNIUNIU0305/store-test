<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class CouponRuleSupplyAR extends ActiveRecord{


    public static function tableName(){
        return '{{%coupon_rule_supply}}';
    }
}
