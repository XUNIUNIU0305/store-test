<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class CouponRecordAR extends ActiveRecord{


    public static function tableName(){
        return '{{%coupon_record}}';
    }
}
