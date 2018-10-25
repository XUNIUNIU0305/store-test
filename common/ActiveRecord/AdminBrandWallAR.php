<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/8
 * Time: 下午4:42
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class AdminBrandWallAR extends ActiveRecord
{

    const DEFAULT_STATUS = 1;
    const NORMAL_STATUS = 0;
    public static function tableName(){
        return '{{%admin_brand_wall}}';
    }
}