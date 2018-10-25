<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class BusinessSmsAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_sms}}';
    }
}
