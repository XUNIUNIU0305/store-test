<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class OrderItemAR extends ActiveRecord{

    public static function tableName(){
        return '{{%order_item}}';
    }
}
