<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/8
 * Time: 下午4:42
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class BrandHomeAR extends ActiveRecord
{

    //热销品牌
    const TYPE_HOT_BRAND = 0;
    //品牌特辑
    const TYPE_BRAND_ALBUM = 1;

    //停用
    const STATUS_UNAVAILABLE = 0;

    //可用
    const STATUS_AVAILABLE = 1;

    //删除
    const STATUS_DELETE = -1;



    public static function tableName(){
        return '{{%brand_home}}';
    }
}