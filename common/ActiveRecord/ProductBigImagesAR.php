<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ProductBigImagesAR extends ActiveRecord{

    //主图排序
    const MAIN_IMAGE_SORT = 0;
    //大图默认排序
    const DEFAULT_SORT = 1;

    public static function tableName(){
        return '{{%product_big_images}}';
    }
}
