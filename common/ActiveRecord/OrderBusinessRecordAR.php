<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class OrderBusinessRecordAR extends ActiveRecord{

    public static function tableName(){
        return '{{%order_business_record}}';
    }
}
