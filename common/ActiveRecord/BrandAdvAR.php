<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/8
 * Time: 下午4:42
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class BrandAdvAR extends ActiveRecord
{
    const POSITION_BIG = 0;
    const POSITION_SMALL = 1;
    const POSITION_LONG = 2;
    public static function tableName(){
        return '{{%brand_adv}}';
    }
}