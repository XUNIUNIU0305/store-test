<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ProductSPUAttributeAR extends ActiveRecord{

    public static function tableName(){
        return '{{%product_spu_attribute}}';
    }
}
