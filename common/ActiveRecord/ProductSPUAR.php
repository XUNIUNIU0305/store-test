<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ProductSPUAR extends ActiveRecord{

    public static function tableName(){
        return '{{%product_spu}}';
    }
}
