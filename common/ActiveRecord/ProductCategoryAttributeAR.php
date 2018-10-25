<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ProductCategoryAttributeAR extends ActiveRecord{

    //默认排序
    const DEFAULT_SORT = 0;

    public static function tableName(){
        return '{{%product_category_attribute}}';
    }
}
