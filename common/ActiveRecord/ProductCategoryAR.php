<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ProductCategoryAR extends ActiveRecord{

    //顶级分类 默认父id
    const TOP_CATEGORY_ID = 0;
    //分类类型：终端分类 | 父分类
    const END_CATEGORY = 1;
    const PARENT_CATEGORY = 0;

    public static function tableName(){
        return '{{%product_category}}';
    }
}
