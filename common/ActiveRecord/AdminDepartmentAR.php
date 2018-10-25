<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/15
 * Time: 16:48
 */

namespace common\ActiveRecord;


use yii\db\ActiveRecord;

class AdminDepartmentAR extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%admin_department}}';
    }

}