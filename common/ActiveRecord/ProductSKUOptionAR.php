<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ProductSKUOptionAR extends ActiveRecord{

    public static function tableName(){
        return '{{%product_sku_option}}';
    }
}
