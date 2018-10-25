<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ProductSPUOptionAR extends ActiveRecord{

    //默认排序
    const DEFAULT_SORT = 0;
    const DISPLAY = 1;
    const HIDE = 0;


    public static function tableName(){
        return '{{%product_spu_option}}';
    }
}
