<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class NanjingRefundAR extends ActiveRecord{

    public static function tableName(){
        return '{{%nanjing_refund}}';
    }
}
