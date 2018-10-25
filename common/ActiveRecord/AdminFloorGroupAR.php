<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/10
 * Time: 上午10:26
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class AdminFloorGroupAR extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%admin_floor_group}}";
    }
}