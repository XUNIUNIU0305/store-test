<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ProductSKUAttributeAR extends ActiveRecord{

    public static function tableName(){
        return '{{%product_sku_attribute}}';
    }
}
