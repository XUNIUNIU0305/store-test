<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class CouponAR extends ActiveRecord{


    public static function tableName(){
        return '{{%coupon}}';
    }
}
