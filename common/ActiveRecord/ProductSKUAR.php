<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ProductSKUAR extends ActiveRecord{

    const CUSTOM_ID_MAX_LENGTH = 255;
    const BAR_CODE_MAX_LENGTH = 255;

    public static function tableName(){
        return '{{%product_sku}}';
    }
}
